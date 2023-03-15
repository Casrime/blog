# Default values
APP_ENV ?= dev
VENDOR_BIN = vendor/bin

##
## # Checks
##---------------------------------------------------------------------------

.PHONY: checks php-cs-fixer phpstan rector twig-cs-fixer

checks: 				## Run checks
checks: 				cs cs-apply twig-cs-fixer phpstan

cs:						## Run php-cs-fixer
						@PHP_CS_FIXER_IGNORE_ENV=true $(VENDOR_BIN)/php-cs-fixer fix --diff --dry-run --verbose

cs-apply:				## Run php-cs-fixer
						@PHP_CS_FIXER_IGNORE_ENV=true $(VENDOR_BIN)/php-cs-fixer fix --diff --verbose

phpstan:				## Run phpstan
						@$(VENDOR_BIN)/phpstan analyse --memory-limit=1G

twig-cs-fixer:			## Run twig-cs-fixer
						@$(VENDOR_BIN)/twig-cs-fixer

##
## # Help
##---------------------------------------------------------------------------

.PHONY: help

help:					## Display help
						@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-20s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

.DEFAULT_GOAL := 	help
