const fs = require('fs');
const CleanCSS = require('clean-css-promise');
const path = require('path');

function readdir(file) {
    return new Promise((resolve, reject) => {
        fs.stat(file, (err, stats) => {
            if(err) {
                reject(err);
            }

            if(stats.isDirectory()) {
                fs.readdir(file, (err, files) => {
                    if(err) {
                        reject(err);
                    }
                    Promise.all(
                        files.map((f) => readdir(path.join(file, f)))
                    ).then(flist => {
                        resolve(flist.reduce(
                            (collector, current) => collector.concat(current),
                            []
                        ))
                    });
                })
            } else {
                resolve(file);
            }
        })
    })
}

function compileCSS() {
    readdir(path.join('css'))
    .then(
        files => files.concat(manualReferences())
    )
    .then(files => new CleanCSS().minify(files))
    .then(css => {
        if(css.errors.length) {
            throw new Error(css.errors);
        }

        fs.writeFile(
            path.join('public/style.css'),
            css.styles,
            err => {
                if(err) {
                    throw new Error(err);
                }
            }
        )
    })
    .catch(err => console.error(err))
}

function manualReferences() {
    return [
        path.join('node_modules/font-awesome/css/font-awesome.css'),
    ];
}

compileCSS();
