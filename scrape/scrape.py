from bs4 import BeautifulSoup
import requests
import os

def add_unfound_plants(plant_name):
    # Add the plant name to unfound_plants.txt
    with open("misc/unfound_plants.txt", "a") as file:
        file.write(f"{plant_name}\n")

def scrape(plant_name, dir_name="imgs_dupe"):
    plant_name = plant_name.replace(" ", "+")
    # Create the Wikipedia URL for the plant
    url = f"https://en.wikipedia.org/wiki/Special:Search?go=Go&search={plant_name}&ns0=1"
    # Send a GET request to the URL
    response = requests.get(url)

    if response.url != f"https://en.wikipedia.org/wiki/{plant_name.replace('+', '_')}":
        
        soup = BeautifulSoup(response.content, "html.parser")
        
        haha = soup.find("div", class_="mw-search-result-heading")
        if haha is None:
            print("No image found")
            add_unfound_plants(plant_name)
            return
        href = haha.find("a")["href"]
        
        url = f"https://en.wikipedia.org{href}"
        
        response = requests.get(url)

    soup = BeautifulSoup(response.content, "html.parser")
    
    img = soup.findAll("img", class_="mw-file-element")
    for i in img:
        if "shackle" in i["src"]:
            img.remove(i)
    try:
        img = img[0]
    except:
        print("No image found")
        add_unfound_plants(plant_name)
        return

    if not os.path.exists(dir_name):
        os.makedirs(dir_name)

    with open(f"{dir_name}/{plant_name}.jpg", "wb") as file:
        img_url = img["src"]
        print(img_url)
        img_data = requests.get(f"https:{img_url}").content
        file.write(img_data)

if __name__ == "__main__":
    with open("misc/plant_names.txt") as file:
        for flower in file:
            # find the name of the plant in parentheses
            # if not found, use the whole line
            flower = flower.strip()
            if "(" in flower:
                flower = flower.split("(")[1].split(")")[0]
            print(flower)
            scrape(flower)