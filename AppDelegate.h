//
//  AppDelegate.h
//  APNS Demonstration
//
//  Created by Terrance Nance on  10/9/20.
//  Copyright (c) 2020 ios. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <UserNotifications/UserNotifications.h>



@interface AppDelegate : UIResponder <UIApplicationDelegate, UNUserNotificationCenterDelegate>


//Make the request IOS Notifications function global so that User Notifications can be requested from different pages
-(void)requestIOSNotifications;


@end
