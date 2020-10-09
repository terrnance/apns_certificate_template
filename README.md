# apns_certificate_template
This template/guide is people who want to create their own Apple Push Notifications Service when building iOS Apps using Xcode (Objective C). The template for sending Remote Notifications was created in PHP.

For token-based notifications, I highly recommend reading the link https://levelup.gitconnected.com/send-push-notification-through-apns-using-node-js-7427a01662a2. It provides a template for push notifications with NodeJS. I am also providing the template for this.

## REQUIRED
To setup your own server, it requires that you have an Apple Developer Account, so
that you can have the necessary permissions and certificates to send Notifications.
You must also have an app created in Xcode in order for notifications to be sent to
your specific app.

You also need a ios Device to receive notifications. The Xcode simulators will not receive
remote notifications.

This guide also utilizes PHP to send the notification data to Apple.

### STEPS

FOLLOW THE YOUTUBE VIDEO to get setup https://www.youtube.com/watch?v=_3YlqWWnI6s

#### PEM File Certificate Code

> Step 0: (FOLLOW THE YOUTUBE VIDEO to get certificates )

> Step 1: Create Certificate .pem from Certificate .p12
- openssl pkcs12 -clcerts -nokeys -out apns-dev-cert.pem -in apns-dev-cert.p12

> Step 2: Create Key .pem from Key .p12
- openssl pkcs12 -nocerts -out apns-dev-key.pem -in apns-dev-key.p12

> Step 3 (NOT NECESSARY): Check the Validity of the Pem Files
- openssl s_client -connect gateway.sandbox.push.apple.com:2195 -cert apns-dev-cert.pem -key apns-dev-key.pem

> Step 4: Create the Final Pem File using Passphrase:
- cat apns-dev-cert.pem apns-dev-key.pem > apns-dev.pem

The apns-dev.pem file and the passphrase you choose in the command prompt will be
used in the php for sending notifications.

#### Add Notification Code to your Xcode Project

Add the necessary code to your AppDelegate Files to register for notifications and get the device token. You will need to add the UIApplicationDelegate and UNUserNotificationCenterDelegate delegates.

#### Setup the Xcode Environment for Notifications

Click on the Project Navigator (folder icon) in the left panel. Click your project's name.
Click Capabilities.
 - Turn Push Notifications "On" Make sure that all check marks are checked.
 - Turn Background Modes "On" and Check "Remote Notifications".

#### Test Your Certificate and Device Token

I highly recommend using the APNS Tester at https://www.apnstester.com/apns/.
They give you the ability to test your certificate and device token. They also provide
a list of resources to help you send notifications.

#### Send Remote IOS Notifications

Open the template.php file. Input your passphrase, and the name of the final pem file.
Input the Device Token that was receive when registering for Notifications.


###  Troubleshooting

- •	Make sure that the php.ini file has the cacerts.pem certificate as the openssl.cafile parameter (Download cacert.pem -> https://curl.haxx.se/docs/caextract.html)
- •	Check that the cacerts.pem is actually accessible
- •	Ensure that the pem certificate file is actually up-to-date and valid (See helpful links below to test certificate) (Also check Apple Developer website that the certificates are valid and project has push notifications enabled)
- •	Make sure that the device token is valid (potentially get new device token – delete app and print device token when registering for remote notifications)
- •	Make sure that the necessary ports are open

## RESOURCES
- Apple Developer Video https://developer.apple.com/videos/play/wwdc2020/10095/
- Showed and walked through the process https://www.youtube.com/watch?v=_3YlqWWnI6s
- Tester for the certificate and pertinent info https://www.apnstester.com/apns/
