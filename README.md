# yangcatalog.org
Static web content for [yangcatalog.org](https://yangcatalog.org/).

## NGINX Configuration

Server Side Includes must be enabled for the static page to work (mainly to have similar header and footer).

```
location / {
       # Allows for server side includes
       ssi on ;
       ssi_last_modified on ;
}
```
