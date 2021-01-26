#!/bin/bash
set -eo pipefail

declare -A compose=(
	[apache]='apache'
	[fpm]='fpm'
	[alpine]='fpm'
)

declare -A base=(
	[apache]='debian'
	[fpm]='debian'
	[alpine]='alpine'
)

variants=(
	apache
	fpm
)

min_version='2.16'
dockerLatest='3.2'


# version_greater_or_equal A B returns whether A >= B
function version_greater_or_equal() {
	[[ "$(printf '%s\n' "$@" | sort -V | head -n 1)" != "$1" || "$1" == "$2" ]];
}

dockerRepo="monogramm/docker-mautic"
# Retrieve automatically the latest versions
latests=( $( curl -fsSL 'https://api.github.com/repos/mautic/mautic/tags' |tac|tac| \
	grep -oE '[[:digit:]]+\.[[:digit:]]+\.[[:digit:]]+' | \
	sort -urV )
)

# Remove existing images
echo "reset docker images"
#find ./images -maxdepth 1 -type d -regextype sed -regex '\./images/[[:digit:]]\+\.[[:digit:]]\+' -exec rm -r '{}' \;
rm -rf ./images/*

echo "update docker images"
travisEnv=
for latest in "${latests[@]}"; do
	version=$(echo "$latest" | cut -d. -f1-2)

	# Only add versions >= "$min_version"
	if version_greater_or_equal "$version" "$min_version"; then

		for variant in "${variants[@]}"; do
			# Create the version directory with a Dockerfile.
			dir="images/$version-$variant"
			if [ -d "$dir" ]; then
				continue
			fi

			echo "updating $latest [$version-$variant]"
			mkdir -p "$dir"

			# Copy the init scripts
			template="Dockerfile.${base[$variant]}.template"
			cp "template/$template" "$dir/Dockerfile"

			for name in nginx.conf makeconfig.php .dockerignore; do
				cp "template/$name" "$dir/$name"
				chmod 755 "$dir/$name"
			done

			cp -r "template/hooks/" "$dir/"
			cp -r "template/test/" "$dir/"
			cp "template/docker-compose_${compose[$variant]}.yml" "$dir/docker-compose.test.yml"

			# Replace the variables.
			sed -ri -e '
				s/%%VARIANT%%/-'"$variant"'/g;
				s/%%VERSION%%/'"$version"'/g;
			' "$dir/Dockerfile"

			sed -ri -e '
				s|DOCKER_TAG=.*|DOCKER_TAG='"${version}-${variant}"'|g;
				s|DOCKER_REPO=.*|DOCKER_REPO='"$dockerRepo"'|g;
			' "$dir/hooks/run"

			# Create a list of "alias" tags for DockerHub post_push
			if [ "$version" = "$dockerLatest" ]; then
				if [ "$variant" = 'apache' ]; then
					export DOCKER_TAGS="$latest-$variant $version-$variant $variant $latest $version latest "
				else
					export DOCKER_TAGS="$latest-$variant $version-$variant $variant "
				fi
			else
				if [ "$variant" = 'apache' ]; then
					export DOCKER_TAGS="$latest-$variant $version-$variant $latest $version "
				else
					export DOCKER_TAGS="$latest-$variant $version-$variant "
				fi
			fi
			echo "${DOCKER_TAGS} " > "$dir/.dockertags"

			# Add Travis-CI env var
			travisEnv='\n    - VERSION='"$version"' VARIANT='"$variant$travisEnv"

			if [[ $1 == 'build' ]]; then
				tag="$version-$variant"
				echo "Build Dockerfile for ${tag}"
				docker build -t ${dockerRepo}:${tag} $dir
			fi
		done
	fi

done

# update .travis.yml
travis="$(awk -v 'RS=\n\n' '$1 == "env:" && $2 == "#" && $3 == "Environments" { $0 = "env: # Environments'"$travisEnv"'" } { printf "%s%s", $0, RS }' .travis.yml)"
echo "$travis" > .travis.yml
