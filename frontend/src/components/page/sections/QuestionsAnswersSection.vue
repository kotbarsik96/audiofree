<template>
    <section class="section questions-answer">
        <div class="container">
            <h3 class="section-title section-title--centered">
                <div>
                    Что стоит знать
                </div>
                <div class="section-title__highlighted">
                    при выборе наушников
                </div>
            </h3>
            <SpoilerTabAdaptive :content="content" isAccordeon></SpoilerTabAdaptive>
        </div>
    </section>
</template>

<script>
import SpoilerTabAdaptive from '@/components/spoiler-tabs/SpoilerTabAdaptive.vue'

export default {
    name: 'QuestionsAnswersSection',
    components: {
        SpoilerTabAdaptive
    },
    data() {
        return {
            content: [
                {
                    button: 'Есть ли гарантия?',
                    text: '<div class="text"><h4 class="title">Гарантия на всю продукцию - 3 месяца</h4><p>Мы ценим доверие, которое вы оказываете, выбирая наши наушники, и гарантируем качество наших продуктов. Все наши наушники поставляются с ограниченной гарантией, чтобы обеспечить вас долгосрочной уверенностью в своей покупке</p><p>Гарантия включает в себя:</p><p>Защиту от производственных дефектов; бесплатный ремонт или замену в случае неисправности; круглосуточную поддержку наших клиентов для вашего комфорта</p><p>Мы гордимся качеством наших продуктов, и наша гарантия доказывает нашу уверенность в них. Мы всегда готовы поддержать вас и вашу покупку. Ваше удовлетворение - наш приоритет!"</p></div>'
                },
                {
                    button: 'Сколько устройств можно подключить?',
                    text: '<div class="text"><h4 class="title">Зависит от выбранной модели</h4><p>Некоторые модели возможно подключить к нескольким устройствам одновременно, таким как смартфоны, планшеты, ноутбуки и другие гаджеты. Это значит, что вы можете слушать музыку или проводить звонки на разных устройствах без необходимости переподключать наушники каждый раз</p><p>Наслаждайтесь беспрерывной музыкой и удобством с нашими наушниками, поддерживающими множественное подключение!</p></div>'
                },
                {
                    button: 'Какой провод использовать?',
                    text: '<div class="text"><p>Правильный выбор провода для ваших наушников может значительно повлиять на качество звука и ваш комфорт при использовании. Наши наушники разработаны с учетом различных потребностей, и мы предоставляем рекомендации относительно выбора провода:</p><p>1. Провод с микрофоном: Если вам важна возможность принимать звонки и управлять музыкой без снятия наушников, выберите провод с встроенным микрофоном и пультом управления</p><p>2. Провод без микрофона: Если вам нужны наушники исключительно для прослушивания музыки, провод без микрофона может предоставить более чистый и высококачественный звук</p><p>3. Беспроводные наушники: Многие из наших моделей также предлагают беспроводную связь через Bluetooth, что обеспечивает максимальную свободу движения и удобство</p><p>4. Универсальность: Большинство наших наушников снабжены разъемами, совместимыми с различными типами проводов, такими как 3.5 мм или USB-C, что делает их универсальными и совместимыми с разными устройствами.</p><p>Не забывайте, что качественный провод способствует лучшему звуку и долговечности ваших наушников. Если у вас возникли вопросы относительно выбора провода, наши эксперты всегда готовы вам помочь. Подберите оптимальный провод, чтобы наслаждаться музыкой с максимальным удовольствием!</p></div>'
                },
            ]
        }
    }
}
</script>

<style lang="scss">
.questions-answer {
    background-color: #F8F5FE;
    padding: 105px 0 115px 0;

    @media (max-width: 992px) {
        padding: 40px 0;
    }
}
</style>