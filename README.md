# Silverstripe Multiple Member Sessions

**This module is for Silverstripe v3.x only since v4 has built-in support for this (`Member has_many RememberLoginHash`)**.

Per default there is only one `RememberLoginToken` per member, which is used for the auto login functionality. This module enables multiple concurrent member sessions.

## Installation
```
composer install level51/silverstripe-multi-member-sessions
```

Run `dev/build flush=all` and you are all done. No further configuration necessary.

## Maintainer
JZubero <js@lvl51.de>