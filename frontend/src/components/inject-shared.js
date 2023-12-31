/* ПОЯСНЕНИЯ:
1. для подключения:
    1) импортируется объект из shared.js для этого компонента (import shared from './shared.js'):
    2) импортируется этот метод (import injectShared from '@/components/inject-shared.js')
    3) export default injectShared(shared, { 
        components: {...}, 
        data(){ return Object.assign({...}, shared) },
        methods: {...},
        ....
    })
2. shared.data возвращает объект, options.data возвращает метод, возвращающий объект, таким образом:
data(){
    return Object.assign({ key: value, ... }, shared.data)
}
3. как работают хуки (mounted, beforeMount и т.дю):
    1) если хук определен в shared и в компоненте одновременно, то сработает только хук из компонента. Поэтому, чтобы сработали оба хука, нужно в хуке компонента вызвать хук из shared в удобном месте (начале или конце, или в середине. Желательно так: 
            if(typeof shared.mounted === 'function') shared.mounted());
    2) если хук определен только в shared, будет вызван только он;
    3) если хук определен только в компоненте, будет вызван только он
*/

export default function (shared, options) {
    const combined = {}

    for (let key in shared) {
        if (key === 'data')
            continue

        const option = options[key]
        if (!option) {
            if (typeof shared[key] === 'object')
                combined[key] = Object.assign({}, shared[key])
            else {
                combined[key] = shared[key]
            }
            continue
        }

        if (shared[key] && typeof shared[key] === 'object') {
            if (!options[key] || typeof options[key] !== 'object')
                options[key] = {}

            combined[key] = Object.assign({}, shared[key], options[key])
        } else {
            if (typeof shared[key] !== 'function')
                combined[key] = shared[key]
        }
    }

    for (let key in options) {
        if (key === 'data') {
            combined.data = options.data
            continue
        }

        if (Object.keys(shared).includes(key)) {
            if (typeof shared[key] !== 'function')
                continue
        }

        if (options[key] && typeof options[key] === 'object')
            combined[key] = Object.assign({}, options[key])
        else
            combined[key] = options[key]
    }


    return combined
}