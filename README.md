

# Install


* Clone this repo
* Composer install
* Create file: config.php

Example file:
```
<?php
define('MR_FONTDATA',
	['arial'=>[
			'R'=>'arial.ttf',
			'I'=>'ariali.ttf'
		]	
	]
);

define('MR_DEFAULT_FONT','arial');
define('MR_DEFAULT_FONT_SIZE','13');
define('MR_API_KEY','abc123');
```
All constants are optional.

If MR_API_KEY is defined you must submit X-API-KEY header in every request;

Service requires 2 parameter, encoded in JSON:

- config - map of config values for Mpdf (used in Mpdf constructor)
- doc - document layout

```
[
	{mpdftype: "html", html: "<h1>Demo!</h1>"},
	{mpdftype: "page", orientation: "P"},
	{mpdftype: "html", html: "<h1>Demo!</h1>"},
]

```

| Type     | Description      | Params                      |
|----------|------------------|-----------------------------|
| html     | Add html content | html                        |
| page     | New page         | orientation = L/P, optional |
| template | Pdf template     | file (path to pdf file)     |

