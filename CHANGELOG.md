# Change Log

## 1.4.0

_Requires WordPress 5.8_
_Tested up to WordPress 6.4_
_Requires BuddyPress 12.0_
_Tested up to BuddyPress 12.2_

### Description

This is the fourth maintenance release of the the BP Classic Add-on. It brings to BuddyPress (12.0.0 & up) backwards compatibility code for plugins & themes not ready yet for the BP Rewrites API.

### Changes

- Make sure bbPress topics/replies pagination is behaving as expected with BuddyPress 12.0 & up (See [#44](https://github.com/buddypress/bp-classic/pull/44)).

## Props

@imath

---

## 1.3.0

_Requires WordPress 5.8_
_Tested up to WordPress 6.4_
_Requires BuddyPress 12.0_
_Tested up to BuddyPress 12.2_

### Description

This is the third maintenance release of the the BP Classic Add-on. It brings to BuddyPress (12.0.0 & up) backwards compatibility code for plugins & themes not ready yet for the BP Rewrites API.

### Changes

- Switch to BP root blog when migrating directories if necessary (See [#33](https://github.com/buddypress/bp-classic/pull/33)).
- Make sure Tooltips are used in Legacy widgets (See [#35](https://github.com/buddypress/bp-classic/issues/35) & [#39](https://github.com/buddypress/bp-classic/issues/39)).
- Use a npm script to get BP Default (See [#37](https://github.com/buddypress/bp-classic/issues/37)).
- Improve how we check BP Nouveau is the current BP Template Pack in use (See [#41](https://github.com/buddypress/bp-classic/issues/41))

## Props

@imath

---

## 1.2.0

_Requires WordPress 5.8_
_Tested up to WordPress 6.4_
_Requires BuddyPress 12.0_
_Tested up to BuddyPress 12.0_

### Description

This is the second maintenance release of the the BP Classic Add-on. It brings to BuddyPress (12.0.0 & up) backwards compatibility code for plugins & themes not ready yet for the BP Rewrites API.

### Changes

- Avoid a type mismatch issue during the migration process (See [#27](https://github.com/buddypress/bp-classic/issues/27)).
- Only check once BuddyPress current config & version are ok (See [#28](https://github.com/buddypress/bp-classic/issues/28)).
- Make sure the migration script is run on Multisite (See [#31](https://github.com/buddypress/bp-classic/issues/31)).

## Props

@imath, @emaralive.

---

## 1.1.0

_Requires WordPress 5.8_
_Tested up to WordPress 6.3_
_Requires BuddyPress 12.0_
_Tested up to BuddyPress 12.0_

### Description

This is the first maintenance release of the the BP Classic Add-on. It brings to BuddyPress (12.0.0 & up) backwards compatibility code for plugins & themes not ready yet for the BP Rewrites API.

### Changes

- Make sure BP Classic is activated at the same network level than BuddyPress (See [#21](https://github.com/buddypress/bp-classic/issues/21)).
- Improve the way the themes directory is registered (See [#23](https://github.com/buddypress/bp-classic/issues/23)).

## Props

@imath, @dd32.

---

## 1.0.0

_Requires WordPress 5.8_
_Tested up to WordPress 6.3_
_Requires BuddyPress 12.0_
_Tested up to BuddyPress 12.0_

### Description

BP Classic brings to BuddyPress (12.0.0 & up) backwards compatibility code for plugins & themes not ready yet for the BP Rewrites API.

### Initial features

- Restores the BP Legacy URL parser.
- Restores the BP Legacy widgets.
- Restores the BP Default theme.
- Restores the BP Legacy navigation globals (`buddypress()->bp_nav` & `buddypress()->bp_options_nav`)

### Props

@imath.
