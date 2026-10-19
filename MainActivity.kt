package za.co.fightclubsa.app

import android.annotation.SuppressLint
import android.os.Bundle
import android.webkit.WebChromeClient
import android.webkit.WebView
import android.webkit.WebViewClient
import androidx.activity.ComponentActivity
import androidx.activity.OnBackPressedCallback
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.ui.Modifier
import androidx.compose.ui.viewinterop.AndroidView

class MainActivity : ComponentActivity() {

    private lateinit var websiteView: WebView

    @SuppressLint("SetJavaScriptEnabled")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        setContent {
            AndroidView(
                modifier = Modifier.fillMaxSize(),

                factory = { context ->
                    WebView(context).apply {
                        settings.javaScriptEnabled = true
                        settings.domStorageEnabled = true

                        webViewClient = WebViewClient()
                        webChromeClient = WebChromeClient()

                        websiteView = this

                        if (savedInstanceState == null) {
                            loadUrl("http://10.0.2.2/FightClubSA_XISD2/")

                        } else {
                            restoreState(savedInstanceState)
                        }
                    }
                }
            )
        }

        onBackPressedDispatcher.addCallback(
            this,
            object : OnBackPressedCallback(true) {
                override fun handleOnBackPressed() {
                    if (
                        ::websiteView.isInitialized &&
                        websiteView.canGoBack()
                    ) {
                        websiteView.goBack()
                    } else {
                        finish()
                    }
                }
            }
        )
    }

    override fun onSaveInstanceState(outState: Bundle) {
        if (::websiteView.isInitialized) {
            websiteView.saveState(outState)
        }

        super.onSaveInstanceState(outState)
    }

    override fun onDestroy() {
        if (::websiteView.isInitialized) {
            websiteView.stopLoading()
            websiteView.destroy()
        }

        super.onDestroy()
    }
}
