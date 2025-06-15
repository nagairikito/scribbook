let modal = document.getElementById("modal");
let modalEditProfile = document.getElementById("profile-editing-modal");
let modalPrivacySetting = document.getElementById("privacy-setting-modal");
let modalFavoriteUser = document.getElementById("favorite-user-modal");
let iconImage = document.getElementById("icon-image");
let editIconImage = document.getElementById("edit-icon-image");

//画像の表示URL
let displayImagePath = "http://localhost/storage/user_icon_images/"



const EDIT_PROFILE           = 1;
const PRIVACY_SETTING        = 2;
const FAVORITE_USER          = 3;

/**
 * モーダルを開く
 * @param {*} $contentsType 
 * @returns 
 */
function openModal($contentsType) {
    modal.classList.replace('close', 'open');

    if($contentsType == EDIT_PROFILE) {
        modalEditProfile.classList.remove('display-none');
        return;
    }
    if($contentsType == PRIVACY_SETTING) {
        modalPrivacySetting.classList.remove('display-none');
        return
    }
    if($contentsType == FAVORITE_USER) {
        modalFavoriteUser.classList.remove('display-none');
        return
    }
    modal.classList.replace('open', 'close');
    return;

}

/**
 * モーダルを閉じる
 * @param {*} $contentsType 
 * @returns 
 */
function closeModal($contentsType) {
    modal.classList.replace('open', 'close');
    
    if($contentsType == EDIT_PROFILE) {
        modalEditProfile.classList.add('display-none');
        return;
    }
    if($contentsType == PRIVACY_SETTING) {
        modalPrivacySetting.classList.add('display-none');
        return
    }
    if($contentsType == FAVORITE_USER) {
        modalFavoriteUser.classList.add('display-none');
        return
    }
    modal.classList.replace('close', 'open');
    return;

}

/**
 * ユーザーアイコンを初期アイコンに戻す
 */
function deleteProfileIconImage() {
    iconImage.src = displayImagePath + "noImage.png";
    editIconImage.value = '';
        console.log(editIconImage)

}

/**
 * バリデーションエラー時のモーダル制御
 */
document.addEventListener('DOMContentLoaded', () => {
    if(document.querySelectorAll(".error-message.me").length > 0) {
        openModal(EDIT_PROFILE)
    }
    if(document.querySelectorAll(".error-message.privacy").length > 0) {
        openModal(PRIVACY_SETTING)
    }

})