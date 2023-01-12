export function styleSetter(tags) {
    const root = document.querySelector(':root');
    Object.keys(tags).forEach((tag) => {
        Object.keys(tags[tag].attributes).forEach((attribute) => {
            root.style.setProperty(
                `--${tag}-${attribute}`,
                tags[tag].attributes[attribute].value +
                    tags[tag].attributes[attribute].unit
            );
        });
    });
}
