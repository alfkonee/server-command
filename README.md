`wp server` — Roots Rewrites
============================

This branch adds rewrites specific for [Roots Theme](https://github.com/retlehs/roots) to [server-command](https://github.com/wp-cli/server-command).

## Explanation

- Adds the function `roots_rewrites()` to [router.php](router.php#L91-L112)
- Uses the added function on [L24 of router.php](router.php#L24)
