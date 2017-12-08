<h2>Encrypted Dropbox</h2>
<p class="lead">Upload your secret documents here. They will be encrypted with your public key, so only the website owner can read it.</p>

<form action="/encrypted-upload/upload" method="post" enctype="multipart/form-data" >
    <div class="form-group">
        <label class="custom-file">
            <input type="file" multiple="true" name="secrets[]" id="file2" class="custom-file-input">
            <span class="custom-file-control"></span>
        </label>
    </div>
    <p class="lead">Your private key is stored in {$CurrentMember.RSAPublicKey.GUID}.txt</p>
    <input type="hidden" name="SecurityID" value="$SecurityID" />
    <button type="submit" class="btn btn-primary btn-large">Upload</button>
</form>

