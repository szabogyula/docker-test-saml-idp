<?php

$config = [
    'admin' => [
        'core:AdminPassword',
    ],
    'example-userpass' => [
        'exampleauth:UserPass',
        'user1:user1pass' => [
            'uid' => ['1'],
            'eduPersonAffiliation' => ['staff'],
            'eduPersonScopedAffiliation' => ['staff@'.getenv('VIRTUAL_HOST')],
            'mail' => 'user1@'.getenv('VIRTUAL_HOST'),
            'eduPersonPrincipalName' => 'user1@'.getenv('VIRTUAL_HOST'),
	    'displayName' => 'User1 Displayname',
        ],
        'user2:user2pass' => [
            'uid' => ['2'],
            'eduPersonAffiliation' => ['staff'],
            'eduPersonScopedAffiliation' => ['staff@'.getenv('VIRTUAL_HOST')],
            'mail' => 'user2@'.getenv('VIRTUAL_HOST'),
            'eduPersonPrincipalName' => 'user2@'.getenv('VIRTUAL_HOST'),
	    'displayName' => 'User2 Displayname',
        ],
    ],
];

if (! empty(getenv('CUSTOM_ATTRIBUTE'))) {
  $config['example-userpass']['user1:user1pass'][getenv('CUSTOM_ATTRIBUTE')] = getenv('CUSTOM_ATTRIBUTE') . "_" . getenv('VIRTUAL_HOST') ."_1";
  $config['example-userpass']['user2:user2pass'][getenv('CUSTOM_ATTRIBUTE')] = getenv('CUSTOM_ATTRIBUTE') . "_" . getenv('VIRTUAL_HOST') ."_2";
}
