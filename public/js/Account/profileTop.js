let modal = document.querySelector('.modal');
let modalEditProfile = document.querySelector('.modal-contents.edit-profile');
let modalPrivacySetting = document.querySelector('.modal-contents.privacy-setting');
let modalFavoriteUser = document.querySelector('.modal-contents.favorite-user');
let iconImage = document.getElementsByName('icon_image');


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
function initUserIcon() {
    iconImage[0].value = 'noImage.png';
}

