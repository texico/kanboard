Plugin Hooks
============

Application Hooks
-----------------

Hooks can extend, replace, filter data or change the default behavior. Each hook is identified with a unique name, example: `controller:calendar:user:events`

### Listen on hook events

In your `initialize()` method you need to call the method `on()` of the class `Kanboard\Core\Plugin\Hook`:

```php
$this->hook->on('hook_name', $callable);
```

The first argument is the name of the hook and the second is a PHP callable.

### Hooks executed only once

Some hooks can have only one listener:

#### model:subtask-time-tracking:calculate:time-spent

- Override time spent calculation when sub-task timer is stopped
- Arguments:
    - `$user_id` (integer)
    - `$start` (DateTime)
    - `$end` (DateTime)

#### model:subtask-time-tracking:calendar:events

- Override subtask time tracking events to display the calendar
- Arguments:
    - `$user_id` (integer)
    - `$events` (array)
    - `$start` (string, ISO-8601 format)
    - `$end` (string, ISO-8601 format)

### Merge hooks

"Merge hooks" act in the same way as the function `array_merge`. The hook callback must return an array. This array will be merged with the default one.

Example to add events in the user calendar:

```php
class Plugin extends Base
{
    public function initialize()
    {
        $container = $this->container;

        $this->hook->on('controller:calendar:user:events', function($user_id, $start, $end) use ($container) {
            $model = new SubtaskForecast($container);
            return $model->getCalendarEvents($user_id, $end); // Return new events
        });
    }
}
```

Example to override default values for task forms:

```php
class Plugin extends Base
{
    public function initialize()
    {
        $this->hook->on('controller:task:form:default', function (array $default_values) {
            return empty($default_values['score']) ? array('score' => 4) : array();
        });
    }
}
```

List of merging hooks:

#### controller:task:form:default

- Override default values for task forms
- Arguments:
    - `$default_values`: actual default values (array)

#### controller:calendar:project:events

- Add more events to the project calendar
- Arguments:
    - `$project_id` (integer)
    - `$start` Calendar start date (string, ISO-8601 format)
    - `$end` Calendar` end date (string, ISO-8601 format)

#### controller:calendar:user:events

- Add more events to the user calendar
- Arguments:
    - `$user_id` (integer)
    - `$start` Calendar start date (string, ISO-8601 format)
    - `$end` Calendar end date (string, ISO-8601 format)

Asset Hooks
-----------

Asset hooks can be used to add a new stylesheet easily or a new JavaScript file in the layout. You can use this feature to create a theme and override all Kanboard default styles.

Example to add a new stylesheet:

```php
<?php

namespace Kanboard\Plugin\Css;

use Kanboard\Core\Plugin\Base;

class Plugin extends Base
{
    public function initialize()
    {
        $this->hook->on('template:layout:css', 'plugins/Css/skin.css');
    }
}
```

List of asset Hooks:

- `template:layout:css`
- `template:layout:js`

Template Hooks
--------------

Template hooks allow to add new content in existing templates.

Example to add new content in the dashboard sidebar:

```php
$this->template->hook->attach('template:dashboard:sidebar', 'myplugin:dashboard/sidebar');
```

This call is usually defined in the `initialize()` method.
The first argument is name of the hook and the second argument is the template name.

Template names prefixed with the plugin name and colon indicate the location of the template.

Example with `myplugin:dashboard/sidebar`:

- `myplugin` is the name of your plugin (lowercase)
- `dashboard/sidebar` is the template name
- On the filesystem, the plugin will be located here: `plugins\Myplugin\Template\dashboard\sidebar.php`
- Templates are written in pure PHP (don't forget to escape data)

Template names without prefix are core templates.

List of template hooks:

| Hook                                 | Description                                        |
|--------------------------------------|----------------------------------------------------|
| `template:analytic:sidebar`          | Sidebar on analytic pages                          |
| `template:app:filters-helper:before` | Filter helper dropdown (top)                       |
| `template:app:filters-helper:after`  | Filter helper dropdown (bottom)                    |
| `template:auth:login-form:before`    | Login page (top)                                   |
| `template:auth:login-form:after`     | Login page (bottom)                                |
| `template:config:sidebar`            | Sidebar on settings page                           |
| `template:config:integrations`       | Integration page in global settings                |
| `template:dashboard:sidebar`         | Sidebar on dashboard page                          |
| `template:export:sidebar`            | Sidebar on export pages                            |
| `template:layout:head`               | Page layout `<head/>` tag                          |
| `template:layout:top`                | Page layout top header                             |
| `template:layout:bottom`             | Page layout footer                                 |
| `template:project:dropdown`          | "Actions" menu on left in different project views  |
| `template:project:header:before`     | Project filters (before)                           |
| `template:project:header:after`      | Project filters (after)                            |
| `template:project:integrations`      | Integration page in projects settings              |
| `template:project:sidebar`           | Sidebar in project settings                        |
| `template:project-user:sidebar`      | Sidebar on project user overview page              |
| `template:task:menu`                 | "Actions" menu on left in different task views     |
| `template:task:dropdown`             | Task dropdown menu in listing pages                |
| `template:task:sidebar`              | Sidebar on task page                               |
| `template:user:authentication:form`  | "Edit authentication" form in user profile         |
| `template:user:create-remote:form`   | "Create remote user" form                          |
| `template:user:external`             | "External authentication" page in user profile     |
| `template:user:integrations`         | Integration page in user profile                   |
| `template:user:sidebar:actions`      | Sidebar in user profile (section actions)          |
| `template:user:sidebar:information`  | Sidebar in user profile (section information)      |


Another template hooks can be added if necessary, just ask on the issue tracker.
