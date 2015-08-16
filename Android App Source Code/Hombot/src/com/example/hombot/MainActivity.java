package com.example.hombot;

import com.example.hombot.R;
import com.example.hombot.helper.SessionManager;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

public class MainActivity extends Activity {
	 
    private TextView txtName;
    private Button btnLogout;
 
    private SessionManager session;
 
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.main);
 
        txtName = (TextView) findViewById(R.id.name);
        btnLogout = (Button) findViewById(R.id.btnLogout);
 
        // session manager
        session = new SessionManager(getApplicationContext());
 
        if (!session.isLoggedIn()) {
            logoutUser();
        }
 
        String name = session.getUserName();
 
        // Displaying the user name on the screen
        txtName.setText(name);
        // Logout button click event
        btnLogout.setOnClickListener(new View.OnClickListener() {
 
            @Override
            public void onClick(View v) {
                logoutUser();
            }
        });
    }
 
    /**
     * Logging out the user. Will set isLoggedIn flag to false and name to unknown in shared preferences
     * */
    private void logoutUser() {
        session.setLogin(false, "unknown");
        // Launching the login activity
        Intent intent = new Intent(MainActivity.this, LoginActivity.class);
        startActivity(intent);
        finish();
    }
}