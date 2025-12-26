package com.example.firstapp;

import android.Manifest;
import android.app.Activity;
import android.app.Activity;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.content.res.Resources;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.ColorMatrix;
import android.graphics.ColorMatrixColorFilter;
import android.graphics.Paint;
import android.graphics.Rect;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.provider.MediaStore;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.core.content.FileProvider;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.IOException;
import java.net.URI;
import java.text.SimpleDateFormat;
import java.util.Date;

public class selfie extends Activity
{
    private static final int CAMERA_REQUEST = 1888;
    private ImageView imageView;
    private static final int MY_CAMERA_PERMISSION_CODE = 100;
    static final int REQUEST_IMAGE_CAPTURE = 1;
    public Integer seasonyear;
    public Integer plotnumber;
    public Integer doctype;
    String currentPhotoPath;
    dbHelper mydb;
    @Override
    public void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_selfie);
        Uri mImageUri;
        Intent myIntent = getIntent();
        seasonyear = Integer.parseInt(String.valueOf(myIntent.getStringExtra("seasonyear")));
        plotnumber = Integer.parseInt(String.valueOf(myIntent.getStringExtra("plotnumber")));
        doctype = Integer.parseInt(String.valueOf(myIntent.getStringExtra("doctype")));
        Intent cameraIntent = new Intent(android.provider.MediaStore.ACTION_IMAGE_CAPTURE);
        if (doctype==1)
        {
            cameraIntent.putExtra("android.intent.extras.CAMERA_FACING", 1);
            cameraIntent.putExtra("android.intent.extras.QUALITY_HIGH",0);
            cameraIntent.putExtra("android.intent.extras.FLASH_MODE_ON",1);
        }
        else if (doctype==2 || doctype==3)
        {
            cameraIntent.putExtra("android.intent.extras.CAMERA_FACING", 0);
            cameraIntent.putExtra("android.intent.extras.QUALITY_HIGH",0);
            cameraIntent.putExtra("android.intent.extras.FLASH_MODE_ON",1);
        }
        try {
            //startActivityForResult(cameraIntent, CAMERA_REQUEST);
            if (cameraIntent.resolveActivity(getPackageManager()) != null) {
                // Create the File where the photo should go
                File photoFile = null;
                try {
                    photoFile = createImageFile();
                } catch (IOException ex) {
                    // Error occurred while creating the File

                }
                // Continue only if the File was successfully created
                if (photoFile != null) {
                    Uri photoURI = FileProvider.getUriForFile(this,
                            "com.example.android.fileprovider",
                            photoFile);
                    cameraIntent.putExtra(MediaStore.EXTRA_OUTPUT, photoURI);
                    startActivityForResult(cameraIntent,REQUEST_IMAGE_CAPTURE);
                }
            }
        }
        catch(Exception e)
        {
            Toast.makeText(this, e.getMessage(), Toast.LENGTH_LONG);
        }
    }
    private File createImageFile() throws IOException {
        // Create an image file name
        String timeStamp = new SimpleDateFormat("yyyyMMdd_HHmmss").format(new Date());
        String imageFileName = "JPEG_" + timeStamp + "_";
        File storageDir = getExternalFilesDir(Environment.DIRECTORY_PICTURES);
        File image = File.createTempFile(
                imageFileName,  /* prefix */
                ".jpg",         /* suffix */
                storageDir      /* directory */
        );

        // Save a file: path for use with ACTION_VIEW intents
        currentPhotoPath = image.getAbsolutePath();
        return image;
    }
    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults)
    {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        if (requestCode == MY_CAMERA_PERMISSION_CODE)
        {
            if (grantResults[0] == PackageManager.PERMISSION_GRANTED)
            {
                Toast.makeText(this, "camera permission granted", Toast.LENGTH_LONG).show();
                Intent cameraIntent = new Intent(android.provider.MediaStore.ACTION_IMAGE_CAPTURE);
                startActivityForResult(cameraIntent, CAMERA_REQUEST);
            }
            else
            {
                Toast.makeText(this, "camera permission denied", Toast.LENGTH_LONG).show();
            }
        }
    }
    private void galleryAddPic() {
        Intent mediaScanIntent = new Intent(Intent.ACTION_MEDIA_SCANNER_SCAN_FILE);
        File f = new File(currentPhotoPath);
        Uri contentUri = Uri.fromFile(f);
        mediaScanIntent.setData(contentUri);
        this.sendBroadcast(mediaScanIntent);
    }
    private Bitmap setPic(Integer w,Integer h) {
        // Get the dimensions of the View
        int targetW = w;
        int targetH = h;

        // Get the dimensions of the bitmap
        BitmapFactory.Options bmOptions = new BitmapFactory.Options();
        bmOptions.inJustDecodeBounds = true;

        BitmapFactory.decodeFile(currentPhotoPath, bmOptions);

        int photoW = bmOptions.outWidth;
        int photoH = bmOptions.outHeight;

        // Determine how much to scale down the image
        int scaleFactor = Math.max(1, Math.min(photoW/targetW, photoH/targetH));

        // Decode the image file into a Bitmap sized to fill the View
        bmOptions.inJustDecodeBounds = false;
        bmOptions.inSampleSize = scaleFactor;
        bmOptions.inPurgeable = true;

        Bitmap bitmap = BitmapFactory.decodeFile(currentPhotoPath, bmOptions);
        return bitmap;
    }
    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data)
    {
        //if (requestCode == CAMERA_REQUEST && resultCode == Activity.RESULT_OK)
        try
        {
            if (requestCode == REQUEST_IMAGE_CAPTURE && resultCode == Activity.RESULT_OK) {
                galleryAddPic();
                //Bitmap photo = (Bitmap) data.getExtras().get("data");
                Bitmap photo = null;
                photo = setPic(320, 240);
                photo = addText(photo);
            /*if (doctype==1 || doctype==2 || doctype==3)
            {
                photo=blacknwhite(photo);
            }*/
                //imageView.setImageBitmap(photo);
                ByteArrayOutputStream stream = new ByteArrayOutputStream();
                photo.compress(Bitmap.CompressFormat.JPEG, 90, stream);
                mydb = new dbHelper(this);
                //1-selfie
                if (doctype == 1) {
                    mydb.updatePlotselfie(seasonyear, plotnumber, stream.toByteArray());
                }
                //2-Id Proof
                else if (doctype == 2) {
                    mydb.updatePlotidproof(seasonyear, plotnumber, stream.toByteArray());
                }
                //3-Passbook
                else if (doctype == 3) {
                    mydb.updatePlotpassbook(seasonyear, plotnumber, stream.toByteArray());
                }
                Intent intent = new Intent(this, verifyplot.class);
                intent.putExtra("seasonyear",seasonyear.toString());
                intent.putExtra("plotnumber",plotnumber.toString());
                Double area = 0.00;
                intent.putExtra("area",area.toString());
                startActivity(intent);
                finish();
                Toast.makeText(this, "फोटो घेतला आहे", Toast.LENGTH_LONG).show();
            }
            else {
                Toast.makeText(this, "फोटो काढला नाही", Toast.LENGTH_LONG).show();
            }
        }
        catch (Exception e)
        {
            Toast.makeText(this, e.getMessage(), Toast.LENGTH_LONG);
        }
    }
    private Bitmap addText(Bitmap toEdit){

        Bitmap dest = toEdit.copy(Bitmap.Config.ARGB_8888, true);
        Canvas canvas = new Canvas(dest);

        Paint paint = new Paint();  //set the look
        paint.setAntiAlias(true);
        paint.setColor(Color.rgb(255,128,64));
        paint.setStyle(Paint.Style.FILL);
        paint.setShadowLayer(2.0f, 1.0f, 1.0f, Color.WHITE);

        int pictureHeight = dest.getHeight();
        paint.setTextSize(pictureHeight * .04629f);

        canvas.drawText("SBThorat SSK" , 10,  dest.getHeight()-20, paint);
        return dest;
    }
    public static Bitmap blacknwhite(Bitmap bmpOriginal){
        int width, height;
        height = bmpOriginal.getHeight();
        width = bmpOriginal.getWidth();
        Bitmap bmpGrayscale = Bitmap.createBitmap(width, height, Bitmap.Config.ARGB_8888);
        Canvas c = new Canvas(bmpGrayscale);
        Paint paint = new Paint();
        ColorMatrix cm = new ColorMatrix();
        cm.setSaturation(0);
        ColorMatrixColorFilter f = new ColorMatrixColorFilter(cm);
        paint.setColorFilter(f);
        c.drawBitmap(bmpOriginal, 0, 0, paint);
        return bmpGrayscale;
    }
}
