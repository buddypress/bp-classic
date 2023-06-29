# BP Classic

+ Requires at least WordPress 5.8
+ Requires at least BuddyPress 12.0

BuddyPress 12.0 introduced the BP Rewrites API: a ~~new~~ right way to parse and route URLs using the WordPress Rewrite API. This important change required BuddyPress to postpone the moment when an URL request is fully analyzed and BP URI Globals are set. The BP Classic plugin is primarly there to provide backward compatibility for plugins or themes which are not ready yet for this change restoring the BuddyPress legacy URL parser.

BP Classic also includes features and templates that were fully deprecated in 12.0 and moved out the BuddyPress plugin for users who are still needing them:

- BP Legacy widgets (these were migrated as Blocks in [BuddyPress 9.0](https://buddypress.org/2021/07/buddypress-9-0-0-mico/)).
- The [BP Default](https://github.com/buddypress/BP-Default) theme.
- The BP Legacy navigation globals (`buddypress()->bp_nav` & `buddypress()->bp_options_nav`).
