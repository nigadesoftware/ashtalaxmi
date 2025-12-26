package com.example.firstapp;

import java.net.InetAddress;

public class Global
{
    public static long uid;
    public static String staticip;

    public boolean isInternetAvailable()
    {
        try {
            InetAddress ipAddr = InetAddress.getByName("google.com");
            //You can replace it with your name
            return !ipAddr.equals("");

        } catch (Exception e) {
            return false;
        }
    }
}

