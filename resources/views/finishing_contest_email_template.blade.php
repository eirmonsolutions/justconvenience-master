<!DOCTYPE html>
<html>
	<head>
	    <meta name="viewport" content="width=device-width">
	    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	    <title>Contest Email</title>
	    <style type="text/css">
	    	/**
	         * Avoid browser level font resizing.
	         * 1. Windows Mobile
	         * 2. iOS / OSX
	         */
	        body,
	        table,
	        td,
	        a {
	          -ms-text-size-adjust: 100%; /* 1 */
	          -webkit-text-size-adjust: 100%; /* 2 */
	        }
	        /**
	         * Remove extra space added to tables and cells in Outlook.
	         */
	        table,
	        td {
	          mso-table-rspace: 0pt;
	          mso-table-lspace: 0pt;
	        }
	        /**
	         * Better fluid images in Internet Explorer.
	         */
	        img {
	          -ms-interpolation-mode: bicubic;
	        }
	        /**
	         * Remove blue links for iOS devices.
	         */
	        a[x-apple-data-detectors] {
	          font-family: inherit !important;
	          font-size: inherit !important;
	          font-weight: inherit !important;
	          line-height: inherit !important;
	          color: inherit !important;
	          text-decoration: none !important;
	        }
	        /**
	         * Fix centering issues in Android 4.4.
	         */
	        div[style*="margin: 16px 0;"] {
	          margin: 0 !important;
	        }
	        body {
	          width: 100% !important;
	          height: 100% !important;
	          padding: 0 !important;
	          margin: 0 !important;
	        }
	        /**
	         * Collapse table borders to avoid space between cells.
	         */
	        table {
	          border-collapse: collapse !important;
	        }
	        a {
	          color: black;
	        }
	        img {
	          height: auto;
	          line-height: 100%;
	          text-decoration: none;
	          border: 0;
	          outline: none;
	        }
	    </style>
	</head>
	<body>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		    <!-- start hero -->
		    <tbody>
			    <!-- start copy block -->
			    <tr>
				    <td align="center" bgcolor="#b5e5e4" style="padding-top: 50px;" >
				        <!--[if (gte mso 9)|(IE)]>
				        <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
				        <tr>
				        <td align="center" valign="top" width="600">
				        <![endif]-->
				        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
				            <tbody>
						        <!-- start copy -->
						        <tr>
						        	<td background="{{ url('assets/images/contest-bg.jpg') }}" align="center" style="padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
						        		<p style="padding-top: 20px; padding-bottom: 10px;"><a href="javascript:void(0);" target="_blank" rel="noopener noreferrer" style="display: inline-block;">
						        			<img src="{{ url('assets/images/white-logo.png') }}" alt="Logo" border="0" width="148" style="display: block; width: 148px; max-width: 148px; min-width: 148px;">
						        		</a></p>
						        		<h1 style="margin: 0 0 2px;font-size: 32px;font-weight: 400;line-height: 48px;color: #ffffff;">Weekly Contest</h1>
						        		<p style="margin: 0;color: #e6ee74;"><b>{{ date('d M Y', strtotime($contest->start_date)) }} - {{ date('d M Y', strtotime($contest->end_date)) }}</b></p>
						        		<p style="margin: 0;color: #ffffff;padding-top: 20px;">{{ $contest->description }}</p>
						        		<p style="margin: 0;padding-top: 20px;color: #ffffff;padding-bottom: 40px;">To participate in the contest click below!</p>

						        		<a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer" style="display: inline-block;padding: 16px 36px;font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif;font-size: 16px;color: #028499;background: #fff;text-decoration: none;border-radius: 6px;font-weight: 500;">Participate Now</a>

						        		<p style="margin: 0;color: #ffffff;padding-top: 30px;padding-bottom: 20px;">Cheers,<br> Team Quicentro Shopping</p>
						        	</td>
						        </tr>
						        <!-- end copy -->
					        </tbody>
					    </table>
				        <!--[if (gte mso 9)|(IE)]>
				        </td>
				        </tr>
				        </table>
				        <![endif]-->
				    </td>
			    </tr>
			    <!-- end copy block -->

			    <!-- start footer -->
			    <tr>
			      	<td align="center" bgcolor="#b5e5e4" style="padding: 24px;">
				        <!--[if (gte mso 9)|(IE)]>
				        <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
				        <tr>
				        <td align="center" valign="top" width="600">
				        <![endif]-->
			        	<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
				          	<!-- start unsubscribe -->
				          	<tbody>
				          		<tr>
			            			<td align="center" bgcolor="" style="padding: 12px 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px; color: #666;">
			              				<p style="margin: 0;">To stop receiving these emails, you can <a href="javascript:void(0);" target="_blank" rel="noopener noreferrer">unsubscribe</a> at any time.</p>
			              				<p style="margin: 0;">Avenida Naciones Unidas entre Avenida 6 de Dicie, bre y Avenida De Los Shyris. Quito, Ecuador</p>
			            			</td>
			          			</tr>
			          			<!-- end unsubscribe -->
				        	</tbody>
				    	</table>
				        <!--[if (gte mso 9)|(IE)]>
				        </td>
				        </tr>
				        </table>
				        <![endif]-->
			      </td>
			    </tr>
			    <!-- end footer -->
		  	</tbody>
		</table>
	</body>
</html>