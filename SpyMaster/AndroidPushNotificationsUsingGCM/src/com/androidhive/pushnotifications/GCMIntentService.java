package com.androidhive.pushnotifications;

import static com.androidhive.pushnotifications.CommonUtilities.EXTRA_MESSAGE;
import static com.androidhive.pushnotifications.CommonUtilities.SENDER_ID;
import static com.androidhive.pushnotifications.CommonUtilities.displayMessage;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicHeader;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.protocol.HTTP;
import org.json.JSONObject;

import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.util.Log;
import android.widget.Toast;

import com.google.android.gcm.GCMBaseIntentService;

public class GCMIntentService extends GCMBaseIntentService {

	private static final String TAG = "GCMIntentService";

    public GCMIntentService() {
        super(SENDER_ID);
    }

    /**
     * Method called on device registered
     **/
    @Override
    protected void onRegistered(Context context, String registrationId) {
        Log.i(TAG, "Device registered: regId = " + registrationId);
        displayMessage(context, "Your device registred with GCM");
        Log.d("NAME", MainActivity.name);
        ServerUtilities.register(context, MainActivity.name, MainActivity.email, registrationId);
    }

    /**
     * Method called on device un registred
     * */
    @Override
    protected void onUnregistered(Context context, String registrationId) {
        Log.i(TAG, "Device unregistered");
        displayMessage(context, getString(R.string.gcm_unregistered));
        ServerUtilities.unregister(context, registrationId);
    }

    /**
     * Method called on Receiving a new message
     * */
    @Override
    protected void onMessage(Context context, Intent intent) {
        Log.i(TAG, "Received message");
        String message = intent.getExtras().getString("price");
        
        displayMessage(context, message);
        // notifies user
        generateNotification(context, message);
		String newMessage = intent.getExtras().getString(EXTRA_MESSAGE);
		// Waking up mobile if it is sleeping
		WakeLocker.acquire(getApplicationContext());
		final String TAG = "MyActivity";
		Log.e(TAG,"onReceive called");
		/**
		 * Take appropriate action on this message
		 * depending upon your app requirement
		 * For now i am just displaying it on the screen
		 * */
		
		// Showing received message
		//lblMessage.append(newMessage + "\n");			
		//Toast.makeText(getApplicationContext(), "New Message: " + newMessage, Toast.LENGTH_LONG).show();
		
		Toast.makeText(getApplicationContext(), "Your download has resumed.", Toast.LENGTH_LONG).show(); 
		
		//sendJSON();
		// Releasing wake lock
		WakeLocker.release();
    }

    private void sendJSON() {
		// TODO Auto-generated method stub
		final String TAG1 = "MyActivity";
		Log.e(TAG1,"sendJSON");
		String url = "";
		new TransferData().execute( url );
	}
    private class TransferData extends AsyncTask<String, Void, String> {


		@Override
		protected String doInBackground(String... url) {
			// TODO Auto-generated method stub
			final String TAG = "MyActivity";
			Log.e(TAG,"doInBackground");
			BufferedReader br = null;
	    	StringBuffer sb = new StringBuffer("");

	            HttpClient client = new DefaultHttpClient();

	            // Set up parameters 
	            HttpConnectionParams.setConnectionTimeout(client.getParams(),
	                    10000); // Timeout Limit

	            // Set up and JSON object & gives the POST message the
	            // "entity" (data) in the form of a string to send to the server. 
	            HttpResponse response;
	            JSONObject json = new JSONObject();
	            String URL = "http://192.168.0.173/mobile/test.php";
	            try {
	                HttpPost post = new HttpPost(URL);
	                json.put("username", "puneet");
	                json.put("password", "xxx");
	                StringEntity se = new StringEntity(json.toString());
	                se.setContentEncoding(new BasicHeader(HTTP.CONTENT_TYPE,
	                        "application/json"));
	                post.setEntity(se);

	                // Execute (send) POST message to target server. 
	                response = client.execute(post);
		    	String line;

		    	br = new BufferedReader(new InputStreamReader(response.getEntity().getContent()));
		    	while ((line = br.readLine()) != null)
		    	{
		    		sb.append(line + "\n");
		    	}
		    	br.close();
		    	
		    } 
	    	catch(Exception e)
	    	{
	    		e.printStackTrace();
	    	}
	    	finally 
	    	{
		    	if (br != null) 
		    	{
		    		try 
		    		{
		    			br.close();
		    		} 
		    		catch(IOException ioe) 
		    		{
		    			ioe.printStackTrace();
		    		}
		    	}	    	
		    	
	    	}			

	    	return(sb.toString());
			
		}
	}
	/**
     * Method called on receiving a deleted message
     * */
    @Override
    protected void onDeletedMessages(Context context, int total) {
        Log.i(TAG, "Received deleted messages notification");
        String message = getString(R.string.gcm_deleted, total);
        displayMessage(context, message);
        // notifies user
        generateNotification(context, message);
    }

    /**
     * Method called on Error
     * */
    @Override
    public void onError(Context context, String errorId) {
        Log.i(TAG, "Received error: " + errorId);
        displayMessage(context, getString(R.string.gcm_error, errorId));
    }

    @Override
    protected boolean onRecoverableError(Context context, String errorId) {
        // log message
        Log.i(TAG, "Received recoverable error: " + errorId);
        displayMessage(context, getString(R.string.gcm_recoverable_error,
                errorId));
        return super.onRecoverableError(context, errorId);
    }

    /**
     * Issues a notification to inform the user that server has sent a message.
     */
    private static void generateNotification(Context context, String message) {
        int icon = R.drawable.ic_launcher;
        long when = System.currentTimeMillis();
        NotificationManager notificationManager = (NotificationManager)
                context.getSystemService(Context.NOTIFICATION_SERVICE);
        Notification notification = new Notification(icon, message, when);
        
        String title = context.getString(R.string.app_name);
        
        Intent notificationIntent = new Intent(context, MainActivity.class);
        // set intent so it does not start a new activity
        notificationIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP |
                Intent.FLAG_ACTIVITY_SINGLE_TOP);
        PendingIntent intent =
                PendingIntent.getActivity(context, 0, notificationIntent, 0);
        notification.setLatestEventInfo(context, title, message, intent);
        notification.flags |= Notification.FLAG_AUTO_CANCEL;
        
        // Play default notification sound
        notification.defaults |= Notification.DEFAULT_SOUND;
        
        //notification.sound = Uri.parse("android.resource://" + context.getPackageName() + "your_sound_file_name.mp3");
        
        // Vibrate if vibrate is enabled
        notification.defaults |= Notification.DEFAULT_VIBRATE;
        notificationManager.notify(0, notification);      

    }

}
