# Change Log

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
