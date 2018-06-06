<template>
    <v-app style="background-color:#000000;">
        <v-content>
            <router-view></router-view>
        </v-content>
    </v-app>
</template>

<script>

    export default {

        props: [ 'app', 'composer' ],
        mounted() {
            console.log('Component mounted.');
            console.log( this.composer.photosDirectory );
        },

        created(){
            this.$store.commit(  'addPhotos', this.composer.photos  );
            this.$store.commit(  'addTerms', this.composer.terms );
            this.$store.commit(  'addPhotosDirectory', this.composer.photosDirectory  );
        },

        data () {
            var composerProp = this.composer;
            return {

                composerProp,
            }
        }
    }
</script>
