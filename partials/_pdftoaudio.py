import argparse # for working with cmd args
import fitz  # this is pymupdf. Extract text page by page
import pyttsx3

def pdfToAudio(args):
    src = args.src
    dest = args.dest

    with fitz.open(src) as doc:
        pymupdf_text = ""
        for page in doc:
            pymupdf_text += page.get_text()

    speak = pyttsx3.init()
    speak.save_to_file(pymupdf_text, dest)
    speak.runAndWait()
    
    

if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument('--src', type=str, help="Provide source file")
    parser.add_argument('--dest', type=str, help="Provide destination file")
    args = parser.parse_args()

    pdfToAudio(args)