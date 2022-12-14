<template>
    <section class="dropDownMenuWrapper">
        <button class="dropDownMenuButton" ref="menu" @click="openClose">{{menuTitle}}</button>
        <div class="iconWrapper">
            <div class="bar1" :class="{ 'bar1--open' : isOpen }" />
            <div class="bar2" :class="{ 'bar2--open' : isOpen }" />
            <div class="bar3" :class="{ 'bar3--open' : isOpen }" />
        </div>
        
        <section class="dropdownMenu" v-if="isOpen">
            <slot/>
        </section>
    </section>
</template>
<script>
export default {
    props: [ "menuTitle" ], // Menu title from the parent
    data() {
        return {
            isOpen: false // Variable if the menu is open or closed
        }
    },
    methods: {
        openClose() 
        {
            var _this = this
            const closeListerner = (e) => {
            if ( _this.catchOutsideClick(e, _this.$refs.menu ) )
                window.removeEventListener('click', closeListerner) , _this.isOpen = false
            }
            window.addEventListener('click', closeListerner)
            this.isOpen = !this.isOpen
        },

        catchOutsideClick(event, dropdown)  
        {
            if( dropdown == event.target )
                return false
  
            if( this.isOpen && dropdown != event.target )
                return true

        }
    }
}
</script>
<style scoped>
.dropDownMenuWrapper {
	position: relative;
	width: 200px;
	height: 45px;
	border-radius: 6px;
	background: #f7f7f7;
    top: 5px;
    margin-right: 10px;
    letter-spacing: 0.5px;
}
.dropDownMenuWrapper * {
	box-sizing: border-box;
	text-align: left;
}
.dropDownMenuWrapper .dropDownMenuButton {
	border: none;
	font-size: inherit;
	background: none;
	outline: none;
	border-radius: 4px;
	position: absolute;
	top: 0;
	left: 0;
	display: flex;
	align-items: center;
	padding: 0 70px 0 20px;
	margin: 0;
	line-height: 1;
	width: 100%;
	height: 100%;
	z-index: 2;
	cursor: pointer;
    color: #777777;
}
.dropDownMenuWrapper .dropDownMenuButton--dark {
	color: #eee;
}
.dropDownMenuWrapper .iconWrapper {
	width: 25px;
	height: 25px;
	position: absolute;
	right: 17px;
	top: 50%;
	transform: translate(0, -50%);
	z-index: 1;
}
.dropDownMenuWrapper .iconWrapper .bar1 {
	width: 100%;
	max-width: 28px;
	height: 3px;
	background: #065ea5;
	position: absolute;
	top: 50%;
	left: 50%;
	border-radius: 9999px;
	transform: translate(-50%, calc(-50% - 8px));
	transition: all 0.2s ease;
}
.dropDownMenuWrapper .iconWrapper .bar1--dark {
	background: #eee;
}
.dropDownMenuWrapper .iconWrapper .bar1--open {
	transform: translate(-50%, -50%) rotate(45deg);
	margin-top: 0;
	background: rgba(228, 52, 54, 1);
}
.dropDownMenuWrapper .iconWrapper .bar2 {
	width: 100%;
	max-width: 28px;
	height: 3px;
	background: #065ea5;
	position: absolute;
	top: 50%;
	left: 50%;
	border-radius: 9999px;
	opacity: 1;
	transform: translate(-50%, -50%);
	transition: all 0.2s ease;
}
.dropDownMenuWrapper .iconWrapper .bar2--dark {
	background: #eee;
}
.dropDownMenuWrapper .iconWrapper .bar2--open {
	opacity: 0;
}
.dropDownMenuWrapper .iconWrapper .bar3 {
	width: 100%;
	max-width: 28px;
	height: 3px;
	background: #065ea5;
	position: absolute;
	top: 50%;
	left: 50%;
	border-radius: 9999px;
	transform: translate(-50%, calc(-50% + 8px));
	transition: all 0.2s ease;
}
.dropDownMenuWrapper .iconWrapper .bar3--dark {
	background: #eee;
}
.dropDownMenuWrapper .iconWrapper .bar3--open {
	top: 50%;
	transform: translate(-50%, -50%) rotate(-45deg);
	background: rgba(228, 52, 54, 1);
}
.dropDownMenuWrapper .iconWrapper--noTitle {
	left: 0;
	top: 0;
	bottom: 0;
	right: 0;
	width: auto;
	height: auto;
	transform: none;
}
.dropDownMenuWrapper .dropdownMenu {
	position: absolute;
	top: 60%;
	width: 100%;
	min-width: 300px;
	min-height: 10px;
	border-radius: 8px;
	border: 1px solid #eee;
	background: white;
	padding: 10px 22px;
	animation: menu 0.3s ease forwards;
    line-height: 31px;
}
.dropDownMenuWrapper .dropdownMenu .menuArrow {
	width: 20px;
	height: 20px;
	position: absolute;
	top: -10px;
	left: 20px;
	border-left: 1px solid #eee;
	border-top: 1px solid #eee;
	background: white;
	transform: rotate(45deg);
	border-radius: 4px 0 0 0;
}
.dropDownMenuWrapper .dropdownMenu .menuArrow--dark {
	background: #333;
	border: none;
}
.dropDownMenuWrapper .dropdownMenu .option {
	width: 100%;
	border-bottom: 1px solid #eee;
	padding: 20px 0;
	cursor: pointer;
	position: relative;
	z-index: 2;
}
.dropDownMenuWrapper .dropdownMenu .option:last-child {
	border-bottom: 0;
}
.dropDownMenuWrapper .dropdownMenu .option * {
	color: inherit;
	text-decoration: none;
	background: none;
	border: 0;
	padding: 0;
	outline: none;
	cursor: pointer;
}
.dropDownMenuWrapper .dropdownMenu .desc {
	opacity: 0.5;
	display: block;
	width: 100%;
	font-size: 14px;
	margin: 3px 0 0 0;
	cursor: default;
}
.dropDownMenuWrapper .dropdownMenu--dark {
	background: #333;
	border: none;
}
.dropDownMenuWrapper .dropdownMenu--dark .option {
	border-bottom: 1px solid #888;
}
.dropDownMenuWrapper .dropdownMenu--dark * {
	color: #eee;
}
@keyframes menu {
	from {
		transform: translate3d(0, 30px, 0);
	}
	to {
		transform: translate3d(0, 20px, 0);
	}
}
.dropDownMenuWrapper--noTitle {
	padding: 0;
	width: 60px;
	height: 60px;
}
.dropDownMenuWrapper--dark {
	background: #333;
	border: none;
}

</style>