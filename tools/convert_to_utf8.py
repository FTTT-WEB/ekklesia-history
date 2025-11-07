#!/usr/bin/env python3
"""
Scan .html/.htm files, detect meta charset declarations for Big5/GB2312 and transcode
them to UTF-8, updating the meta tag to charset=utf-8.

Usage:
  python3 tools/convert_to_utf8.py --apply
  python3 tools/convert_to_utf8.py --dry-run

This script overwrites files when --apply is used. Make a git branch/backup first.
"""
import argparse
import os
import re
from pathlib import Path


ENC_MAP = {
    b'big5': 'big5',
    b'gb2312': 'gb18030',
    b'gbk': 'gb18030',
}

# For cases where the declared encoding fails to decode, try these fallbacks
FALLBACKS = {
    'big5': ['big5hkscs', 'cp950', 'gb18030'],
    'gb2312': ['gb18030', 'gbk'],
}


def detect_declared_charset(head_bytes: bytes):
    hb = head_bytes.lower()
    for key in ENC_MAP:
        if key in hb:
            return key.decode('ascii')
    return None


META_RE = re.compile(r'(<meta[^>]*charset\s*=\s*["\']?)([^"\'>\s]+)(["\'>])', flags=re.I)


def replace_meta_charset(text: str) -> str:
    def repl(m):
        return m.group(1) + 'utf-8' + m.group(3)
    return META_RE.sub(repl, text)


def process_file(path: Path, apply: bool):
    with path.open('rb') as f:
        data = f.read()

    head = data[:4096]
    charset = detect_declared_charset(head)
    if not charset:
        return False, None

    src_enc = ENC_MAP.get(charset.encode('ascii'))
    if not src_enc:
        return False, None

    decode_error = None
    tried_enc = []
    # try declared encoding first, then fallbacks
    enc_candidates = [src_enc] + FALLBACKS.get(src_enc, [])
    for enc in enc_candidates:
        tried_enc.append(enc)
        try:
            text = data.decode(enc)
            decode_error = None
            break
        except Exception as e:
            decode_error = e
            text = None

    # If declared/fallback encodings failed, but the bytes are actually valid UTF-8,
    # treat the file as already UTF-8 and simply update the meta tag.
    if text is None:
        try:
            text = data.decode('utf-8')
            # proceed — we'll update meta tag but not re-decode/encode
        except Exception:
            return False, f'decode-failed ({tried_enc}): {decode_error}'

    new_text = replace_meta_charset(text)

    if not apply:
        return True, 'would-convert'

    # write UTF-8
    tmp_path = path.with_suffix(path.suffix + '.utf8tmp')
    with tmp_path.open('w', encoding='utf-8') as f:
        f.write(new_text)
    # move into place
    tmp_path.replace(path)
    return True, 'converted'


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument('--apply', action='store_true', help='Actually write converted files')
    parser.add_argument('--root', default='.', help='Root directory to process')
    args = parser.parse_args()

    root = Path(args.root)
    exts = {'.htm', '.html', '.shtml'}
    converted = 0
    candidates = 0
    failed = 0

    for dirpath, dirnames, filenames in os.walk(root):
        # skip .git
        if '.git' in dirpath.split(os.sep):
            continue
        for fn in filenames:
            p = Path(dirpath) / fn
            if p.suffix.lower() not in exts:
                continue
            ok, msg = process_file(p, args.apply)
            if ok and msg in ('would-convert','converted'):
                candidates += 1
                if args.apply:
                    converted += 1
                    print(f'Converted: {p}')
                else:
                    print(f'Would convert: {p}')
            elif ok:
                # ok but no conversion needed
                pass
            else:
                failed += 1
                print(f'Failed/Skipped {p}: {msg}')

    print('Summary: candidates=%d converted=%d failed=%d' % (candidates, converted, failed))


if __name__ == '__main__':
    main()
