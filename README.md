hypeDiscovery
=============

Enhanced social presence and discovery of Elgg content in the social eco-system.


## Introduction

hypeDiscovery allows users to create public description of otherwise inaccessible
content items (e.g. if your site is in a walled garden mode). The plugin implements
a system of permalinks that are accessible by remote services, such as Facebook,
Twitter, Google Plus, LinkedIn, Pinterest etc. 


## Features

* Extensible OpenGraph metatags
* Extensible Twitter Card metatags
* oEmbed provider
* Permalinks and Embed Code
* Self-hosted Share buttons (no external javascript or cookies)
* Granular control over discoverable and embeddable entity types

## Developer Notes

### Tracking Shares

Each permalink includes a unique user hash, so you can track the shares and referrals
back to the sharing user.

Use ```'entity:share', $entity_type``` hook for tracking shares.
Use ```'entity:referred', $entity_type``` hook for tracking traffic generated by the user's share

### Images

In cases, where content icons are not available to outside world, you can use
```'entity:icon:filestore', $entity_type``` hook, to specify the location of the
the location of the icon on the filestore.

### Metatags

To modify OpenGraph or other metatags, use ```'metatags', 'discovery'``` hook.


### Embeds

To control the output of the embedded content, see the ```oembed``` viewtype views.


## Screenshots

![alt text](https://raw.github.com/hypeJunction/hypeDiscovery/master/screenshots/entity_share.png "Share")
![alt text](https://raw.github.com/hypeJunction/hypeDiscovery/master/screenshots/entity_discovery.png "Discovery Settings")
![alt text](https://raw.github.com/hypeJunction/hypeDiscovery/master/screenshots/site_profile.png "Site Profile")