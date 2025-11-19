git config core.hooksPath githooks
git submodule --quiet update --init --recursive --remote
echo "✅ Updated githooks config successfully"