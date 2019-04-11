<template>
    <div class="home-container">
        <div class="notepad rappel-corner">
            <div class="notepad-header">
                <h1>Rappel</h1>
            </div>
            <div class="notepad-body row no-gutters">
                <div class="notepad-left col-2"></div>
                <div class="notepad-main col-10">
                    <hr>
                    <hr>
                    <hr>
                    <hr>
                    <hr>
                    <hr>
                    <hr>
                    <hr>
                    <hr>
                    <hr>
                    <hr>
                    <hr>
                    <hr>
                    <hr>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
    @import '~@/app.scss';

    .home-container {
        
        .notepad {
            @include absoluteCenter;
            width: 500px;
            background: #fff;

            &-header {
                border-bottom: 1px solid rgba(132, 0, 23, 0.76);
                padding: 0.5rem;

                h1 {
                    margin-bottom: 0;
                }
            }

            &-left {
                border-right: 1px solid rgba(0, 0, 0, 0.1);
            }

            &-main {
                hr {
                    margin: 1.5rem 0;
                }
            }
        }
    }
</style>