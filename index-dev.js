import sass from "sass";
import { promisify } from "util";
import { writeFile } from "fs";

const sassRenderPromise = promisify(sass.render);
const writeFilePromise = promisify(writeFile);

async function main() {

  const styleResult = await sassRenderPromise({
    file: `${process.cwd()}/styles.scss`,
    outFile: `${process.cwd()}/style.css`,
    sourceMap: true,
    sourceMapContents: true,
    outputStyle: "expanded",   // change to "compressed" for production version
  });

//  console.log(styleResult.css.toString());

  await writeFilePromise("style.css", styleResult.css, "utf8");
  await writeFilePromise("style.css.map", styleResult.map, "utf8");
}

main();
