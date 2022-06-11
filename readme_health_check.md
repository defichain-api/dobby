# Health Check

Dobby offers two types of health checks: system relevant checks and vault based checks.

### System Checks

We're keeping track of our system.

The health monitoring is public visible at [status.defichain-dobby.com](https://status.defichain-dobby.com/).

### Vault base checks

Dobby offers a public URL to check the health of your vault.

The URL scheme is:
``https://api.defichain-dobby.com/health/vault/{vault_id}/{ratio}``

As you can see, there are two parameters:
- `vault_id`: the ID of the monitored vault
- `ratio`: integer value of the ratio, when the endpoint returns an error (status code of error: `422`)

A healthy check looks like:

```
{
  "checkOk": true,
  "nextRatio": 160.41
}
```

On error, the `checkOk` turn to `false`.
