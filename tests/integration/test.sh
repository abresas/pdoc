#!/bin/bash
set -eu
set -o pipefail

TEST_DOCS_DIR=/tmp/pdoc__test
TEST_WORKDIR=/tmp/pdoc__test__workdir
rm -rf $TEST_DOCS_DIR || true
rm -rf $TEST_WORKDIR || true

cp -r . $TEST_WORKDIR
mkdir $TEST_DOCS_DIR

cd $TEST_WORKDIR

rm build/pdoc.phar >/dev/null 2>&1 || true
composer install --no-dev >/dev/null 2>&1
box build
php build/pdoc.phar lib $TEST_DOCS_DIR
diff docs $TEST_DOCS_DIR && echo "Success" || echo "Fail"

rm -rf $TEST_DOCS_DIR
rm -rf $TEST_WORKDIR
