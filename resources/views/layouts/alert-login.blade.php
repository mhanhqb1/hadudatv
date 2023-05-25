<?php
$adsUrl = 'https://www.highrevenuegate.com/yyd0y6ju?key=067f678dd58695fe284b02ecb1e3d6bb';
?>
<style>
.modal-content {
    position: relative;
    display: -webkit-box;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,.2);
    border-radius: 0.3rem;
    outline: 0;
}
.mopie-modal-content {
    background-position: 50%;
    background-repeat: no-repeat;
    background-size: cover;
}
.ex-icon {
    position: absolute;
    top: -3rem;
    width: 6rem;
    height: 6rem;
    /* line-height: 8rem; */
    border-radius: 50%;
    background-color: #f1e248;
    display: flex;
    align-items: center;
    font-size: 80px;
    text-align: center;
    width: 100;
    flex: 1;
    justify-content: space-around;
    font-weight: bold;
    color: #000;
}
.mt-3, .my-3 {
    margin-top: 1rem!important;
}
.modal-content > .modal-body > div {
    color: #fff;
    padding: 1rem;
    padding-top: 3rem;
    -webkit-box-align: center!important;
    align-items: center!important;
    position: relative;
    -webkit-box-pack: center!important;
    justify-content: center!important;
    -webkit-box-orient: vertical!important;
    flex-direction: column!important;
    display: flex;
}
.modal-content > .modal-body > div .h3 {
    margin-top: 1rem!important;
    font-size: 1.575rem;
    font-weight: 500;
    line-height: 1.2;
}
.modal-content > .modal-body > div > p {
    margin-bottom: 1rem;
    font-size: .9rem;
    font-weight: 400;
    line-height: 1.6;
}
.form-control {
    display: block;
    width: 100%;
    height: calc(1.6em + 0.75rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: .9rem;
    font-weight: 400;
    line-height: 1.6;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    -webkit-transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
.form-group {
    margin-bottom: 1rem;
}
.modal-body {
    position: relative;
    -webkit-box-flex: 1;
    flex: 1 1 auto;
    padding: 1rem;
}
</style>
<div class="modal-content">
    <div class="modal-body mopie-modal-content p-0 border" style="background-image: url('//image.tmdb.org/t/p/original/6VX3TrYBtnMOHp3v44lIWWnEp7N.jpg')">
        <div class="align-items-center d-flex flex-column justify-content-center position-relative p-3 pt-5 text-center">
            <div class="ex-icon">
                !
            </div>
            <div class="h3 font-bold mt-3">Activate your FREE Account!</div>
            <p>You must create an account to continue watching</p>
            <a href="{{ $adsUrl }}" target="_blank" class="flex items-center px-2 py-2 font-medium tracking-wide text-white capitalize transition-colors duration-200 transform bg-blue-600 rounded-md hover:bg-blue-500 focus:outline-none focus:bg-blue-500">Continue to watch for FREE ➞</a>
        </div>
    </div>
    <div class="modal-footer align-items-center d-flex flex-column justify-content-center text-center text-dark">
        <div class="modal-body clearfix" style="width: 100%;">
            <div class="col-md-12" id="login">
                <div class="form-group"><input type="email" class="form-control input-md" id="userid" placeholder="Enter email"></div>
                <div class="form-group"><input type="password" class="form-control input-md" id="password" placeholder="Password"></div>
                <div class="form-group">
                    <span class="onload label label-info" style="display: none;font-size: 100%;">Loading...</span>
                    <span class="onerror label label-warning" style="display: none;font-size: 100%;">Wrong Username or Password</span>
                </div>
                <input type="submit" id="subregs" style="margin: 0 auto;" class="flex items-center px-2 py-2 font-medium tracking-wide text-white capitalize transition-colors duration-200 transform bg-blue-600 rounded-md hover:bg-blue-500 focus:outline-none focus:bg-blue-500" value="Sign In" onclick="sowerr()">
                <p id="logfail" style="margin-top:10px; display:none;">Account not found! Please <a target="_blank" href="{{ $adsUrl }}" style="color: blue;">register here..</a></p>
                <script>
                    function sowerr() {
                        document.getElementById("logfail").style.display = "block";
                    };
                </script>
            </div>
        </div>
    </div>
</div>
