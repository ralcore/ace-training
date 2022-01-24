# this file initialises a working environment for the website.
# currently mostly used for mongodb setup.

# constant for echoing aesthetics
$line = "-----------------"
$break = "`n`n`n"

# ------- GETTING WORKING LOCATION
# get working location
$location=Get-Location
$location=$location.tostring()
$wwwindex=$location.IndexOf('\www\')
$workingdir=$location.Substring(0,$wwwindex+4)

# check that we're in the right place
Write-Host "detected uniserverz install at path $workingdir"
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

# ------- INSTALLING MONGODB
# downloading mongodb files from website!
Write-Host "directory confirmed. beginning mongodb download..."
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
$destinationpath = $workingdir
# skip unzip if folder already found
if(!(Test-Path $destinationpath -PathType Container)) {
	Expand-Archive -LiteralPath $ziplocation -DestinationPath $destinationpath
	Write-Host "archive extracted. installing..."
} else {
	Write-Host "found existing extracted mongodb folder. installing..."
}
Read-Host -Prompt "yea"