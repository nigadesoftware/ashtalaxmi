package com.example.firstapp;

public class ListItem
{
    private Integer serialnumber;
    private Double latitude;
    private Double longitude;
    private float distance;
    public Integer getSerialnumber() {
        return serialnumber;
    }
    public void setSerialnumber(Integer serialnumber) {
        this.serialnumber = serialnumber;
    }
    public Double getLatitude() {
        return latitude;
    }
    public void setLatitude(Double latitude) {
        this.latitude = latitude;
    }
    public Double getLongitude() {
        return longitude;
    }
    public void setLongitude(Double longitude) {
        this.longitude = longitude;
    }
    public float getDistance() {
        return distance;
    }
    public void setDistance(float distance) {
        this.distance = distance;
    }
}