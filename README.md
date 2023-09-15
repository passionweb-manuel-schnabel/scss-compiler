
# SCSS Compiler for TYPO3

SCSS Compiler Extension for compiling scss files.


## Installation

Install SCSS Compiler with composer

```bash
  composer req passionweb/scss-compiler
```
## Setup

Include the template file `SCSS Compiler` within your root template.

## Usage

1. Add following code snippet to your extensions setup.typoscript, add your scss file(s) and include the template:

   page = PAGE
   page {
      includeCSS {
         {EXTENSION_NAME}_theme1 = EXT:{EXTENSION_NAME}/Resources/Public/Scss/Theme/theme1.scss
         {EXTENSION_NAME}_theme2 = EXT:{EXTENSION_NAME}/Resources/Public/Scss/Theme/theme2.scss
      }
   }

You can add your scss file(s) with includeCSS or includeCSSLibs.


2. Relative path from extension root to your theme files must always be EXT:{EXTENSION_NAME}/Resources/Public/Scss/Theme/
3. Add composer dependency to extension(s) which should use the scss compiler to prevent unwanted issues
4. If you use the EXT:bootstrap_package disable the CSS pre processing option in the extension settings. Otherwise scss files from other extensions except the EXT:bootstrap_package will be ignored
5. The new generated asset(s) will be placed in public/typo3temp/assets/compiledscss/css/UNIQUENAME.css
