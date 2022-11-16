# IAP App Engine example

A simple monolithic PHP application deployed to [Google Cloud Platform - App Engine](https://cloud.google.com/appengine) to demonstrate [Identity Aware Proxy (IAP)](https://cloud.google.com/iap/) and  prints the following (unsigned) [request headers](https://cloud.google.com/iap/docs/identity-howto#getting_the_users_identity_with_signed_headers) supplied by IAP:

 * `X-Goog-Authenticated-User-Id`
 * `X-Goog-Authenticated-User-Email`

**NOTE:** This demo is NOT using [Signed JWT headers](https://cloud.google.com/iap/docs/signed-headers-howto) which are advised for production use.

[Additional headers](https://cloud.google.com/appengine/docs/standard/reference/request-headers?tab=python#app_engine-specific_headers) are provided by App Engine and used in the generated output for demonstration purposes.

Since this is a demo, all headers are printed in the page as a hidden comment - you can see them by viewing the page source (right-click > View Page Source) and viewing the section marked with: `<!-- #debug information, all headers`.

## Deployment

Best practice is to separate each app into it's own project. It's important to note that only one OAuth Consent Screen can be configured per project and cannot be destroyed. Additionally, an App Engine deployment cannot easily be destroyed, only disabled. To destroy both of this will require deletion of the project.

Create a new project for the application to be deployed into.

**After you create a new GCP Project**, with the project selected, open Cloud Shell and run:

```sh
# Checkout
git clone https://github.com/aitalian/iap-appengine-example.git
cd iap-appengine-example

# Create App Engine environment
gcloud app create --project=$(gcloud config get-value project) --region=us-central

# Deploy
gcloud app deploy

# View the URL - open it in your browser
gcloud app browse

# Copy the output from the following command - it will be used for the OAuth Consent Screen
export AUTH_DOMAIN=$(gcloud config get-value project).uc.r.appspot.com
echo $AUTH_DOMAIN
```

Open the URL in your browser and observe that the application is openly accessible.

The next step will be to protect it using IAP.

## Protect with IAP

Once deployed, protect the app's endpoint using IAP. Go to **Security > Identity Aware Proxy**, and then **Enable API** (if not already enabled).

### Configure OAuth Consent Screen

Once enabled, go back to **Security > Identity Aware Proxy**, and follow the prompts to configure the **OAuth Consent Screen**:

 * **User Type:** Internal
 * **Application Name:** IAP Example
 * **User Support email:** *your email*
 * **Authorized domain:** Add the value of `$AUTH_DOMAIN` from the Cloud Shell output.
 * **Developer contact email:** *your email*

*Save and Continue* leaving all other default values.

### Enable IAP on App Engine application

Go back to the **Security > Identity Aware Proxy** page and toggle the **IAP** button next to your App Engine app in the list. The **Published** column will contain your protect app's URL. Copy and paste this into your browser to test.

Observe that *Access is denied*. The app has been protected by IAP, and you are not an authorized user (yet).

To add authorized users, click on the **Add Member** button. Add IAM Principals with the Role: **Cloud IAP > IAP-secured Web App User**. You can also select an Access Level if configured.

Try and access the same URL. You will see an OAuth Consent Screen named as you configured it earlier, and will be asked to authorize your account. Proceed with the same account you added as an authorized user. Observe that the app should be accessible, and display additional information that was not available prior to enabling IAP.

## Troubleshooting

### Clear IAP Session Cookie

If you need to "log out", you will need to create the session cookie created by IAP. To do this, append the following to the Published App URL: `/_gcp_iap/clear_login_cookie` and then try your request again.
