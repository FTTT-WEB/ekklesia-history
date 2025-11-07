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

    try:
        text = data.decode(src_enc)
    except Exception as e:
        return False, f'decode-failed: {e}'

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
