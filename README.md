# iap-appengine-example

A simple PHP application that prints the following request headers supplied by IAP:

 * `X-Goog-Authenticated-User-Id`
 * `X-Goog-Authenticated-User-Email`
 
To deploy, create a new GCP Project, and then in Cloud Shell run:

```sh
# Checkout
git clone <repo_url>

# Create App Engine environment
gcloud app create --project=$(gcloud config get-value project) --region=us-central

# Deploy
gcloud app deploy

# View the URL
gcloud app browse

# Copy the following that will be used for the OAuth Consent Screen
export AUTH_DOMAIN=$(gcloud config get-value project).uc.r.appspot.com
echo $AUTH_DOMAIN
```

Once deployed, protect it using IAP by going to Security > Identity Aware Proxy, and then Enable API. Once enabled, Go To Identity Aware Proxy and configure the OAuth Consent Screen:

 * **Application Name:** IAP Example
 * **Support email:** <enter your email>
 * **Authorized domain:** Add the value of `$AUTH_DOMAIN` from the Cloud Shell output.
 * **Developer contact email:** <enter your email>

Save and Continue leaving all other default values.

Go back to the Identity Aware Proxy page and toggle the **IAP** button next to your App Engine app in the list. The **Published** column will contain your protect app's URL. Copy and paste this into your browser to test.

To add authorized users, click on the **Add Member** button. Add IAM Principals with Role: **Cloud IAP > IAP-secured Web App User**.

To clear the session cookie created by IAP, append the following to the Published App URL: `/_gcp_iap/clear_login_cookie` and then try your request again.


