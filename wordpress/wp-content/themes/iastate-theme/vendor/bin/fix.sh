#!/bin/bash

#if [ -n "$(type -t php-cs-fixer)" ] && [ "$(type -t php-cs-fixer)" = function ]; then
#php-cs-fixer fix vendor/iastate-theme/php/src/IastateTheme/Theme.php --rules=array_syntax
    find . -type f -name "Theme.php" -exec php-cs-fixer fix --rules=array_syntax '{}' \;
#fi
find . -type f -name "Theme.php" -exec sed -i '' -e 's/private function renderCarousel/public function renderCarousel/g' {} \;
