import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'public/css/CommonParts/main.css',
                'public/css/accountRegistrationForm.css',
                'public/css/blogDetail.css',
                'public/css/blogEditing.css',
                'public/css/blogPosting.css',
                'public/css/blogUnit.css',
                'public/css/common.css',
                'public/css/loginForm.css',
                'public/css/profileTop.css',
                'public/css/search.css',
                'public/css/style.css',
                'public/css/talkRoom.css',
                'public/css/talkRoomList.css',
                'public/css/toppage.css',
                'resources/js/app.js',
                'public/js/blogEditing.js',
                'public/js/blogPosting.js',
                'public/js/blogUnitSizeSetting.js',
                'public/js/common.js',
                'public/js/displayScreenSizeSetting.js',
                'public/js/profileTop.js',
                'public/js/search.js',
                'public/js/talkRoomList.js',
                'public/js/topPageGetScreenSize.js',
            ],
            refresh: true,
        }),
    ],
});
