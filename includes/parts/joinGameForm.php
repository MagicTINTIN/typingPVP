<div class="joinGameForms">
    <form method="post" class="connectionForm">
        <input type="text" class="input authorDateInput authorDateCommon gameInput" name="gameInput" id="gameInput" required maxlength="250" placeholder="Nom de la partie">


        <div class='zone3'>
            <input type="submit" class="input joinSubmit" id="enterGame" value="Rejoindre la partie" name="enterGame">
        </div>

    </form>

    <form method="post" class="connectionForm" action="host">
        <input type="submit" class="input createSubmit" id="createGame" value="Créer une partie" name="createGame">
    </form>
</div>