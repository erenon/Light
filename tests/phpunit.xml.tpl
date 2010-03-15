<?xml version="1.0" encoding="UTF-8"?>
<phpunit
	colors="false"
	bootstrap="TestHelper.php"
>
	<!-- <listeners>
		<listener class="PHPUnit_Extensions_TicketListener_GitHub" 
		              file="/PHPUnit/Extensions/TicketListener/GitHub.php">
			<arguments>
				<string>##Username##</string>
				<string>##API_key##</string>
				<string>##Repository_name##</string>
				<boolean>true</boolean>
			</arguments>
		</listener>
	</listeners>-->	
	<testsuites>
		<testsuite name="application">
			<testsuite name="modules">
				<testsuite name="default">
					<testsuite name="page">
						<file>application/modules/default/models/PageTest.php</file>
						<file>application/modules/default/models/PageFileMapperTest.php</file>
						<file>application/modules/default/services/PageTest.php</file>
					</testsuite>
				</testsuite>
			</testsuite>
		</testsuite>
	</testsuites>
</phpunit>