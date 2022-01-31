# this file initialises a working environment for the website.
# currently mostly used for mongodb setup.

# constants for echoing aesthetics
$line = "-----------------"
$break = "`n"

# ------- GETTING WORKING LOCATION
# get working location
$location=Get-Location
$location=$location.tostring()
$wwwindex=$location.IndexOf('\www\')
if ($wwwindex -eq -1) {
	Write-Host "initialisation failed, as uniserverz can't be found. try making sure this script (and by extension all of the website's files) are inside your uniserverz' /www/ folder"
	Write-Host $line
	Read-Host -Prompt "enter to close"
	exit
}
$workingdir=$location.Substring(0,$wwwindex+4)

# check that we're in the right place
Write-Host "detected uniserverz install at path $workingdir" -ForegroundColor yellow
Write-Host $line
# not even close to foolproof, so checking with the user everything seems right.
do {
	$in = Read-Host -Prompt "is this the correct path to your uniserverz www folder? (y/n)"
} while("y","n" -notcontains $in)
Write-Host $break
# if it's wrong, kill script
if ($in -eq "n") {
	Write-Host "initialisation failed, as uniserverz can't be found. try making sure this script (and by extension all of the website's files) are inside your uniserverz' /www/ folder"
	Write-Host $line
	Read-Host -Prompt "enter to close"
	exit
}

# ------- INSTALLING MONGODB DRIVER
# getting download from pecl
Write-Host "directory confirmed. beginning mongodb php driver download..."
$downloadlink = "https://windows.php.net/downloads/pecl/releases/mongodb/1.12.0/php_mongodb-1.12.0-8.0-ts-vs16-x64.zip"
$phplocation = $workingdir.Substring(0, $wwwindex) + "\core\php80"
$mongodbphplocation = $phplocation + "\extensions"
# skip download if already downloaded
if(!(Test-Path ($mongodbphplocation + "\php_mongodb.dll") -PathType Leaf)) {
	Invoke-WebRequest -Uri $downloadlink -OutFile ($mongodbphplocation + "\php_mongodb.zip")
	Write-Host "download complete. extracting..."
	Expand-Archive -LiteralPath ($mongodbphplocation + "\php_mongodb.zip") -DestinationPath ($mongodbphplocation + "\php_mongodb")
	Move-Item ($mongodbphplocation + "\php_mongodb\php_mongodb.dll") $mongodbphplocation
	Remove-Item ($mongodbphplocation + "\php_mongodb") -Recurse
	Remove-Item ($mongodbphplocation + "\php_mongodb.zip")
	Write-Host "mongodb php driver installed. adding to php extensions file..."
} else {
	Write-Host "found existing mongodb driver install. adding to php extensions file..."
}

# adding extension to php test file (which uniserverz uses by default)
# NOTE: IF UNISERVERZ EVER CHANGES INI FILE THIS WILL NEED CHANGING
(Get-Content -path ($phplocation + "\php_test.ini") -Raw) -replace ';extension=mongodb','extension=mongodb' | Set-Content -Path ($phplocation + "\php_test.ini")



# ------- INSTALLING MONGODB
# downloading mongodb files from website!
Write-Host "added to file. beginning mongodb download..."
$downloadlink = "https://fastdl.mongodb.org/windows/mongodb-windows-x86_64-5.0.5.zip"
$ziplocation = $workingdir + "\mongodb.zip"
# skip download if already downloaded
if(!(Test-Path $ziplocation -PathType Leaf)) {
	Invoke-WebRequest -Uri $downloadlink -OutFile $ziplocation
	Write-Host "download complete. extracting..."
} else {
	Write-Host "found existing mongodb download. extracting..."
}

# unzipping file
$toolspath = $workingdir + "\dev-tools"
# check for and make dev-tools path
if(!(Test-Path $toolspath -PathType Container)) {
	New-Item -Path $toolspath -ItemType directory
}
$mongopath = $toolspath + "\mongodb"
# skip unzip if folder already found
if(!(Test-Path $mongopath -PathType Container)) {
	Expand-Archive -LiteralPath $ziplocation -DestinationPath $toolspath
	Rename-Item ($toolspath + "\mongodb-win32-x86_64-windows-5.0.5") $mongopath
	Write-Host "archive extracted. starting server..."
} else {
	Write-Host "found existing extracted mongodb folder. starting server..."
}

# ------- STARTING MONGODB SERVER
$mongodatapath = $mongopath + "\data"
# making data directory if it does not exist
if(!(Test-Path $mongodatapath -PathType Container)) {
	New-Item -Path $mongodatapath -ItemType directory
}
# starting mongodb server in new powershell
# relevant if modified https://github.com/PowerShell/PowerShell/issues/5576
# Clear-Host
# $s = New-PSSession
# Invoke-Command -Session $s -ScriptBlock {$host.ui.RawUI.WindowTitle = "MongoDB Shell"; &($mongopath + "\bin\mongod.exe") --dbpath $mongodatapath}
$argumentlist = '-noexit -noprofile -command "$host.ui.RawUI.WindowTitle = \"MongoDB Server\"; &"' + $mongopath + '"\"\\bin\mongod.exe\" --dbpath "' + $mongodatapath
Start-Process -FilePath powershell -ArgumentList $argumentlist

# ------- STARTING MONGODB SHELL
# ask user if this should happen
	Write-Host $break
	Write-Host "mongodb has been installed and is running!" -ForegroundColor yellow
	Write-Host "the newly opened powershell window is running your mongodb server."
	Write-Host "when you wish to shut down the development environment, please shut it down safely using --> CTRL+C <--"
	Write-Host "do you want to open the mongodb shell?"
	Write-Host "(this will allow you to directly interface with the database)"
	Write-Host $line
do {
	$in = Read-Host -Prompt "open mongodb shell? (y/n)"
} while("y","n" -notcontains $in)
Write-Host $break
# if shell unwanted, end init
if ($in -eq "n") {
	Write-Host "work environment initialised!" -ForegroundColor yellow
	Write-Host $line
	Read-Host -Prompt "enter to close"
	exit
}
Write-Host "work environment initialised!" -ForegroundColor yellow
Write-Host "this window will now open the mongodb shell."
Write-Host $line
Read-Host -Prompt "enter to continue to mongodb shell..."
Write-Host "starting mongodb shell..."
$host.ui.RawUI.WindowTitle = 'MongoDB Shell'
&($mongopath + "\bin\mongo.exe")
Read-Host -Prompt "ada"

