# iap-appengine-example

A simple PHP application deployed to Google Cloud Platform - App Engine to demonstrate Identity Aware Proxy (IAP) and  prints the following request headers supplied by IAP:

 * `X-Goog-Authenticated-User-Id`
 * `X-Goog-Authenticated-User-Email`

Additional headers are provided by App Engine and used in the generated output for demonstration purposes.

Since this is a demo, all headers are printed in the page as a hidden comment - you can see them by viewing the page source (right-click > View Page Source) and viewing the section marked with: `<!-- #debug information, all headers`.

## Deployment

To deploy, create a new GCP Project, and then in Cloud Shell run:

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

Observe that the application is openly accessible. The next step will be to protect it using IAP.

## Protect with IAP

Once deployed, protect the app's endpoint using IAP. Go to **Security > Identity Aware Proxy**, and then **Enable API** (if not already enabled).

Once enabled, go back to **Security > Identity Aware Proxy**, and follow the prompts to configure the **OAuth Consent Screen**:

 * **User Type:** Internal
 * **Application Name:** IAP Example
 * **User Support email:** *your email*
 * **Authorized domain:** Add the value of `$AUTH_DOMAIN` from the Cloud Shell output.
 * **Developer contact email:** *your email*

*Save and Continue* leaving all other default values.

Go back to the **Security > Identity Aware Proxy** page and toggle the **IAP** button next to your App Engine app in the list. The **Published** column will contain your protect app's URL. Copy and paste this into your browser to test.

Observe that *Access is denied*. The app has been protected by IAP, and you are not an authorized user (yet).

To add authorized users, click on the **Add Member** button. Add IAM Principals with the Role: **Cloud IAP > IAP-secured Web App User**. You can also select an Access Level if configured.

Try and access the same URL. You will see an OAuth Consent Screen named as you configured it earlier, and will be asked to authorize your account. Proceed with the same account you added as an authorized user. Observe that the app should be accessible, and display additional information that was not available prior to enabling IAP.

## Troubleshooting

### Clear IAP Session Cookie

If you need to "log out", y ou will need to create the session cookie created by IAP. To do this, append the following to the Published App URL: `/_gcp_iap/clear_login_cookie` and then try your request again.
