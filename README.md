# Docker Test SAML 2.0 Identity Provider (IdP)


![Seal of Approval](docs/seal.jpg)

Docker container with a plug and play SAML 2.0 Identity Provider (IdP) for development and testing.

Built with [SimpleSAMLphp](https://simplesamlphp.org). Based on official PHP8.3 Apache [images](https://hub.docker.com/_/php/).

**Warning!**: Do not use this container in production! The container is not configured for security and contains static user credentials and SSL keys.

SimpleSAMLphp is logging to stdout on debug log level. Apache is logging error and access log to stdout.

The contained version of SimpleSAMLphp is 2.2.2.

## Usage

```
docker run --name=testsamlidp_idp \
-p 8080:80 \
-e VIRTUAL_HOST=app.example.com \
-e NO_HTTPS=true \
-e PORT=8081 \
-e IDP_ENTITY_ID=http://example/idp \
-e MDX_URL=https://mdx.eduid.hu/entities \
-e SCOPE=false \
-e CUSTOM_ATTRIBUTE=custom_id
-d szabogyula/test-saml-idp
```

If set the optional SCOPE env variable to true the shibmd:scope will be the VIRTUAL_HOST tag in the metadata.

The IDP_ENTITY_ID NO_HTTPS PORT METADATA_URL variables are optional.

If set the optional CUSTOM_ATTRIBUTE env variable to any value ie: custom_id the saml attribute get a value: <attribute_name>_<virtual_host>_<uid>.

There are two static users configured in the IdP with the following data:

| UID | Username | Password  | Group  | Email             | custom_id                   |
|-----|----------|-----------|--------|-------------------|-----------------------------|
| 1   | user1    | user1pass | group1 | user1@example.com | custom_id_app.exmaple.com_1 |
| 2   | user2    | user2pass | group2 | user2@example.com | custom_id_app.exmaple.com_2 |

However you can define your own users by mounting a configuration file:

```
-v /users.php:/var/www/simplesamlphp/config/authsources.php
```

You can set user population by USERS_JSON environment variable. Take a look at [dockerker-compose.yml](docker-compose.yml).

```json
{
    "alice:alice": {
        "uid": ["1"],
        "eduPersonAffiliation": ["staff"],
        "eduPersonScopedAffiliation": ["staff@localhost"],
        "mail": "alice@localhost",
        "eduPersonPrincipalName": "alice@localhost",
        "displayName": "Alice Displayname"
    },
    "bob:bob": {
        "uid": ["2"],
        "eduPersonAffiliation": ["staff"],
        "eduPersonScopedAffiliation": ["staff@localhost"],
        "mail": "bob@localhost",
        "eduPersonPrincipalName": "bob@localhost",
        "displayName": "Bob Displayname"
    }
}
```

You can access the SimpleSAMLphp web interface of the IdP under `http://localhost:8080/simplesaml`. The admin password is `secret`.

## Test the Identity Provider (IdP)

To ensure that the IdP works you can use SimpleSAMLphp as test SP.

Download a fresh installation of [SimpleSAMLphp](https://simplesamlphp.org) and configure it for your favorite web server.

For this test the following is assumed:
- The entity id of the SP is `http://app.example.com`.
- The local development URL of the SP is `http://localhost`.
- The local development URL of the IdP is `http://localhost:8080`.

The entity id is only the name of SP and the contained URL wont be used as part of the auth mechanism.

Add the following entry to the `config/authsources.php` file of SimpleSAMLphp.
```
    'test-sp' => array(
        'saml:SP',
        'entityID' => 'http://app.example.com',
        'idp' => 'http://localhost:8080/simplesaml/saml2/idp/metadata.php',
    ),
```

Add the following entry to the `metadata/saml20-idp-remote.php` file of SimpleSAMLphp.
```
$metadata['http://localhost:8080/simplesaml/saml2/idp/metadata.php'] = array(
    'name' => array(
        'en' => 'Test IdP',
    ),
    'description' => 'Test IdP',
    'SingleSignOnService' => 'http://localhost:8080/simplesaml/saml2/idp/SSOService.php',
    'SingleLogoutService' => 'http://localhost:8080/simplesaml/saml2/idp/SingleLogoutService.php',
    'certFingerprint' => '119b9e027959cdb7c662cfd075d9e2ef384e445f',
);
```

Start the development IdP with the command above (usage) and initiate the login from the development SP under `http://localhost/simplesaml`.

Click under `Authentication` > `Test configured authentication sources` > `test-sp` and login with one of the test credentials.


## Contributing

See [CONTRIBUTING.md](https://github.com/kristophjunge/docker-test-saml-idp/blob/master/docs/CONTRIBUTING.md) for information on how to contribute to the project.

See [CONTRIBUTORS.md](https://github.com/kristophjunge/docker-test-saml-idp/blob/master/docs/CONTRIBUTORS.md) for the list of contributors.


## License

This project is licensed under the MIT license by Kristoph Junge.
