<div class="jumbotron">
    <div class="container">
        <div class="row">
            <div class="col-12">

                <% if not CurrentMember %>
                    not logged in
                <% else_if not CurrentMember.RSAPublicKey.PublicKey %>
                    public key not loaded yet
                <% else %>
                    <% include EncryptedDropbox %>
                <% end_if %>

            </div>
        </div>
    </div>
</div>
$Form
