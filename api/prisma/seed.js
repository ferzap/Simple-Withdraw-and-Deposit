const { PrismaClient } = require("@prisma/client");
const { hashPassword } = require("../src/api/utils/helper");
const prisma = new PrismaClient();

async function main() {
    const firman = await prisma.user.upsert({
        where: { email: "firman.er@gmail.com" },
        update: {},
        create: {
            email: "firman.er@gmail.com",
            name: "Firman Erza",
            password: await hashPassword("123456"),
            wallet: {
                create: {
                    balance: 245000.5,
                },
            },
        },
    });

    const erza = await prisma.user.upsert({
        where: { email: "erza.firman@gmail.com" },
        update: {},
        create: {
            email: "erza.firman@gmail.com",
            name: "Erza Firman",
            password: await hashPassword("123456"),
            wallet: {
                create: {
                    balance: 750450.2,
                },
            },
        },
    });
    console.log({ firman, erza });
}
main()
    .then(async () => {
        await prisma.$disconnect();
    })
    .catch(async (e) => {
        console.error(e);
        await prisma.$disconnect();
        process.exit(1);
    });
