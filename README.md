osofflineim
===========

Grid Offline Messages - Mwi Module

Opensim INI settings: change http://yourdomain.com to your domain

```ini
[Messaging]
    ;# {OfflineMessageModule} {} {Module to use for offline message storage} {OfflineMessageModule *}
    ;; Module to handle offline messaging. The core module requires an external
    ;; web service to do this. See OpenSim wiki.
    OfflineMessageModule = OfflineMessageModule

    ;# {OfflineMessageURL} {OfflineMessageModule:OfflineMessageModule} {URL of offline messaging service} {}
    ;; URL of web service for offline message storage
    OfflineMessageURL = http://yourdomain.com/modules/im

    ;# {MuteListModule} {OfflineMessageModule:OfflineMessageModule} {} {} MuteListModule
    ;; Mute list handler (not yet implemented). MUST BE SET to allow offline
    ;; messages to work
    MuteListModule = MuteListModule

    ;# {MuteListURL} {OfflineMessageModule:OfflineMessageModule} {} {} http://yourserver/Mute.php
    ;; URL of the web service that serves mute lists. Not currently used, but
    ;; must be set to allow offline messaging to work.
    MuteListURL = http://yourdomain.com/modules/im/mute

    ;; Control whether group messages are forwarded to offline users.
    ;; Default is true.
    ;; This applies to the core groups module (Flotsam) only.
    ForwardOfflineGroupMessages = true
```

