#!/bin/bash
set -eu
set -o pipefail

TEST_DIR=/tmp/pdoc__test
rm -rf $TEST_DIR || true

mkdir $TEST_DIR
php cli/pdoc.php lib $TEST_DIR
diff docs $TEST_DIR

rm -rf $TEST_DIR
