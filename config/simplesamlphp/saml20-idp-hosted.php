<?php
if (getenv('IDP_ENTITY_ID') != '') {
    $entityId = getenv('IDP_ENTITY_ID');
} else {
    $entityId = 'https://'. getenv('VIRTUAL_HOST') .'/simplesaml/saml2/idp/metadata.php';
}

$metadata[$entityId] = [
    'host' => '__DEFAULT__',

    'privatekey' => 'server.pem',
    'certificate' => 'server.crt',
    'sign.logout' => true,
    'redirect.sign' => true,

    'auth' => 'example-userpass',

    'attributes.NameFormat' => 'urn:oasis:names:tc:SAML:2.0:attrname-format:uri',
    'userid.attribute' => 'uid',

    'authproc' => [
        // 20 => [
        //     'class' => 'core:TargetedID',
        //     'identifyingAttribute' => 'eduPersonPrincipalName',
        // ],
        30 => 'core:LanguageAdaptor',
        49 => [
            'class' => 'core:AttributeMap', 'name2oid'
        ],
        50 => 'core:AttributeLimit',
    ],
];

$metadata[$entityId]['scope'] = [ getenv('VIRTUAL_HOST') ];
if ( getenv('SCOPE') == "false" ) {
  unset ($metadata[$entityId]['scope']);
};
