package com.androidhive.pushnotifications;

import static com.androidhive.pushnotifications.CommonUtilities.DISPLAY_MESSAGE_ACTION;
import static com.androidhive.pushnotifications.CommonUtilities.EXTRA_MESSAGE;
import static com.androidhive.pushnotifications.CommonUtilities.SENDER_ID;

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

import android.app.Activity;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.gcm.GCMRegistrar;


public class MainActivity extends Activity {
	// label to display gcm messages
	TextView lblMessage;
	
	// Asyntask
	AsyncTask<Void, Void, Void> mRegisterTask;
	
	// Alert dialog manager
	AlertDialogManager alert = new AlertDialogManager();
	
	// Connection detector
	ConnectionDetector cd;
	
	public static String name;
	public static String email;

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		
		cd = new ConnectionDetector(getApplicationContext());

		// Check if Internet present
		if (!cd.isConnectingToInternet()) {
			// Internet Connection is not present
			alert.showAlertDialog(MainActivity.this,
					"Internet Connection Error",
					"Please connect to working Internet connection", false);
			// stop executing code by return
			return;
		}
		
		// Getting name, email from intent
		Intent i = getIntent();
		
		name = i.getStringExtra("name");
		email = i.getStringExtra("email");		
		
		// Make sure the device has the proper dependencies.
		GCMRegistrar.checkDevice(this);

		// Make sure the manifest was properly set - comment out this line
		// while developing the app, then uncomment it when it's ready.
		GCMRegistrar.checkManifest(this);

		lblMessage = (TextView) findViewById(R.id.lblMessage);
		
		registerReceiver(mHandleMessageReceiver, new IntentFilter(
				DISPLAY_MESSAGE_ACTION));
		
		// Get GCM registration id
		final String regId = GCMRegistrar.getRegistrationId(this);

		// Check if regid already presents
		if (regId.equals("")) {
			// Registration is not present, register now with GCM			
			GCMRegistrar.register(this, SENDER_ID);
		} else {
			// Device is already registered on GCM
			if (GCMRegistrar.isRegisteredOnServer(this)) {
				// Skips registration.				
				Toast.makeText(getApplicationContext(), "Already registered with GCM", Toast.LENGTH_LONG).show();
			} else {
				// Try to register again, but not in the UI thread.
				// It's also necessary to cancel the thread onDestroy(),
				// hence the use of AsyncTask instead of a raw thread.
				final Context context = this;
				mRegisterTask = new AsyncTask<Void, Void, Void>() {

					@Override
					protected Void doInBackground(Void... params) {
						// Register on our server
						// On server creates a new user
						ServerUtilities.register(context, name, email, regId);
						return null;
					}

					@Override
					protected void onPostExecute(Void result) {
						mRegisterTask = null;
					}

				};
				mRegisterTask.execute(null, null, null);
			}
		}
	}		

	/**
	 * Receiving push messages
	 * */
	private final BroadcastReceiver mHandleMessageReceiver = new BroadcastReceiver() {
		@Override
		public void onReceive(Context context, Intent intent) {
			String newMessage = intent.getExtras().getString(EXTRA_MESSAGE);
			// Waking up mobile if it is sleeping
			WakeLocker.acquire(getApplicationContext());
			final String TAG = "MyActivity";
			Log.e(TAG,"onReceive called");
			Log.i("Message recienved",newMessage);

			/**
			 * Take appropriate action on this message
			 * depending upon your app requirement
			 * For now i am just displaying it on the screen
			 * */
			
			// Showing received message
			//lblMessage.append(newMessage + "\n");			
			//Toast.makeText(getApplicationContext(), "New Message: " + newMessage, Toast.LENGTH_LONG).show();
			
			Toast.makeText(getApplicationContext(), newMessage, Toast.LENGTH_LONG).show(); 
			
			sendJSON();
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
	};
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
	@Override
	protected void onDestroy() {
		if (mRegisterTask != null) {
			mRegisterTask.cancel(true);
		}
		try {
			unregisterReceiver(mHandleMessageReceiver);
			GCMRegistrar.onDestroy(this);
		} catch (Exception e) {
			Log.e("UnRegister Receiver Error", "> " + e.getMessage());
		}
		super.onDestroy();
	}

}
